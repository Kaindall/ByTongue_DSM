<?php
require_once 'src\main\domain\model\exception\ia\gemini\LevelRangeException.php';
require_once 'src\main\domain\model\exception\ia\gemini\OriginLanguageException.php';
require_once 'src\main\domain\model\exception\ia\gemini\TargetLanguageException.php';

class GeminiClient {
    private static $chat = [];
    private string $method = "POST";
    private array $contents = ["contents" => []];
    public function __construct(
        private string $url,
        ) {}

        public function sendMessage(string $message) {
            $this->contents['contents'][] = [
                'role' => 'user',
                'parts' => [
                    ['text' => json_decode($message, true)['content']]
                ]
            ];
            
            
            return json_encode($this->contents); 
            
            /* $httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json'])
                ->withHttpMethod($this->method)
                ->withBody(self::$contents)
                ->build();
            $response = $httpClient->executeRequest($this->url);

            return $response; */
        }

        public function getQuestions(array $args) {
            $quantity = $args['quantity'] ?? 5;
            $level = CEFRLevel::fromInt(2);
            $langOrigin = 'português';
            $langDestiny = 'inglês';

            if (isset($args['level'])) {
                $level = CEFRLevel::fromInt($args['level']);
                if ($args['level'] < 0 && $args['level'] > 10) {throw new LevelRangeException();}
            }
            if (isset($args['from'])) {
                $langOrigin = Locale::getDisplayLanguage($args['from']);
                if ($langOrigin === $args['from']) {throw new OriginLanguageException();}
            }
            if (isset($args['to'])) {
                $langDestiny = Locale::getDisplayLanguage($args['to']);
                if ($langDestiny === $args['to']) {throw new TargetLanguageException();}
            }

            $prompt = <<<PROMPT
            Você é um professor de idiomas ($langDestiny), ensinando um aluno em $langOrigin.
            Considerando um aluno nível $level->value do CEFR.
            Faça $quantity perguntas sobre idiomas. Ou seja, que nivelam o conhecimento da pessoa em $langDestiny.
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
            $this->contents['contents'][] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $prompt]
                ]
            ];


            $httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json', 'Accept: application/json'])
                ->withHttpMethod($this->method)
                ->withBody($this->contents)
                ->build();

            $response = $httpClient->executeRequest($this->url);
            $normalizedResponse = json_decode(json_decode($response, true)['candidates'][0]['content']['parts'][0]['text']);
            return json_encode($normalizedResponse, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT); 
        }
    }
