<?php
require_once 'src/main/domain/service/ia/IaClient.php';
require_once 'src/main/domain/service/ia/Quizzeable.php';
require_once 'src/main/infrastructure/config/Logger.php';
require_once 'src/main/domain/model/exception/ia/gemini/EmptyBodyException.php';
require_once 'src/main/domain/model/exception/ia/gemini/InvalidBodyException.php';
require_once 'src/main/domain/model/exception/ia/gemini/LevelRangeException.php';
require_once 'src/main/domain/model/exception/ia/gemini/OriginLanguageException.php';
require_once 'src/main/domain/model/exception/ia/gemini/TargetLanguageException.php';
require_once 'src/main/domain/model/exception/ia/gemini/InvalidQuantityException.php';
require_once 'src/main/domain/model/exception/ia/gemini/UnexpectedGeminiException.php';

class GeminiClient implements IaClient, Quizzeable {
    private string $method = "POST";
    private array $body = [
        "contents" => []
    ];
    public function __construct(
        private string $url,
        ) {}

        public function commitMessage(array $message, array $instructs) {
            $this->body['system_instruction'] = $instructs;
            $this->body['contents'] = $message;

            $httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json'])
                ->withHttpMethod($this->method)
                ->withBody($this->body)
                ->build();

            $response = $httpClient->executeRequest($this->url);
            $normalizedResponse = json_decode($response, true)['candidates'][0]['content'] ?? null;
            if ($normalizedResponse === null) {
                Logger::warning('Retorno inesperado. Repetindo mais uma vez...');
                $response = $httpClient->executeRequest($this->url);
                $normalizedResponse = json_decode($response, true)['candidates'][0]['content'] ?? null;

                if ($normalizedResponse === null) {throw new UnexpectedGeminiException($response);}
            }
            return $normalizedResponse;
        }

        public function getExam(array $args) {

            $quantity = $args['quantity'];
            $level = $args['level'];
            $langOrigin = $args['origin'];
            $langDestiny = $args['target'];

            $prompt = <<<PROMPT
            Você é um professor de idiomas ($langDestiny), ensinando um aluno em $langOrigin.
            Considerando um aluno nível $level->value do CEFR.
            Faça $quantity perguntas sobre nuances de idiomas para testar o usuário. Leve em consideração o formato de uma prova.
            Retorne apenas um Json puro, sem markdown, formatação ou blocos de código.
            Respeite o seguinte formato Json:
            [
                {
                    "question": Pergunta aqui,
                    "options": [
                        "Option1", "Option2", "Option3", "Option4", "Option5"
                    ],
                    "correct": Índice da resposta correta.  
                }
            ]
            PROMPT;
            $this->body['contents'][] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt]
                ]
            ];

            $httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json', 'Accept: application/json'])
                ->withHttpMethod($this->method)
                ->withBody($this->body)
                ->build();

            $response = $httpClient->executeRequest($this->url);
            Logger::debug("Resposta do Gemini: " . PHP_EOL . $response);
            $responseArray = json_decode($response, true);
            $jsonString = $responseArray['candidates'][0]['content']['parts'][0]['text'] ?? '';
            $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', trim($jsonString));
            $normalizedResponse = json_decode($cleanedJsonString, true);
            if ($normalizedResponse === null) {
                Logger::warn('Retorno inesperado. Repetindo mais uma vez...');
                $response = $httpClient->executeRequest($this->url);
                $responseArray = json_decode($response, true);
                $jsonString = $responseArray['candidates'][0]['content']['parts'][0]['text'] ?? '';
                $cleanedJsonString = preg_replace('/^```json\s*|\s*```$/', '', trim($jsonString));
                $normalizedResponse = json_decode($cleanedJsonString, true);

                if ($normalizedResponse === null) {throw new UnexpectedGeminiException($response);}
            }
            Logger::debug("Resposta normalizada: " . PHP_EOL . $normalizedResponse);
            return json_encode($normalizedResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }
    }
