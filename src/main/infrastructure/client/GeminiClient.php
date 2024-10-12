<?php

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
            
            /*$httpClient = (new HttpClientBuilder())
                ->withHeaders(['Content-Type: application/json'])
                ->withHttpMethod($this->method)
                ->withBody($this->contents)
                ->build();
            $response = $httpClient->executeRequest($this->url);

            return $response; */
        }
    }
