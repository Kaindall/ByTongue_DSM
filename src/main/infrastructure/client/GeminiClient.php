<?php

class GeminiClient {
    private string $method = "POST";
    private array $contents = ["contents" => []];
    public function __construct(
        private string $url,
        ) {}

        public function sendMessage(string $message) {
            $this->contents['contents'][] = [
                'role' => 'user',
                'parts' => [
                    ['text' => $message]
                ]
            ];

            
            
            return json_encode($this->contents);

            /* $data = [
                'http' => [
                    'header' => ['Content-Type: application/json'],
                    'method'=> 'POST',
                    'content' => $this->contents,
                    'timeout' => 60
                ]
            ]; 
             $requestContext = stream_context_create($data);
            $response = file_get_contents($this->url, false, $requestContext);

            if ($response === false) {
                $error = error_get_last();
                echo "HTTP Error: " . $error['message'];
            } else {
                echo $response;
            } */
        }
    }
