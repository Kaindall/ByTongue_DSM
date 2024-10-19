<?php
require_once 'src\main\domain\model\exception\ia\gemini\EmptyBodyException.php';
require_once 'src\main\domain\model\exception\ia\gemini\InvalidBodyException.php';
require_once 'src\main\domain\model\exception\ia\gemini\LevelRangeException.php';
require_once 'src\main\domain\model\exception\ia\gemini\OriginLanguageException.php';
require_once 'src\main\domain\model\exception\ia\gemini\TargetLanguageException.php';
require_once 'src\main\domain\model\exception\ia\gemini\InvalidQuantityException.php';

class GeminiClient {
    private string $method = "POST";
    private array $contents = ["contents" => []];
    public function __construct(
        private string $url,
        ) {}

        public function commitMessage(?string $message) {
            $file = 'temp_data.json';

            $formattedMessage = [
                'role' => 'user',
                'parts' => [
                    ['text' => $message['content']]
                ]
            ];

            if (!file_exists($file)) {
                $this->contents['contents'][] = $formattedMessage;
                file_put_contents($file, json_encode($this->contents), JSON_PRETTY_PRINT);
                //echo 'Json criado com sucesso!' . PHP_EOL;
                return json_encode($this->contents);
            } 
            
            $data = json_decode(file_get_contents($file), true);
            //echo 'Json consultado' . PHP_EOL;

            $this->contents = $data;
            $this->contents['contents'][] = $formattedMessage;
            file_put_contents($file, json_encode($this->contents), JSON_PRETTY_PRINT);

            

            return json_encode($this->contents); 
            
            /* $httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json'])
                ->withHttpMethod($this->method)
                ->withBody(self::$contents)
                ->build();
            $response = $httpClient->executeRequest($this->url);

            return $response; */
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
