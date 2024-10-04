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

            $data = [
                'http' => [
                    'header' => ['Content-Type: application/json'],
                    'method'=> 'POST',
                    'content' => $this->contents,
                    'timeout' => 60
                ]
            ];

            $req = curl_init($this->url);
            curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, CURLOPT_POST, true);
            curl_setopt($req, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
            ]);
            curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($this->contents));

            $response = curl_exec($req);

            if ($response === false) {
                $error = curl_error($req);
                return $error;
            } else {
                return $response;
            }

            curl_close($req);

            /* $requestContext = stream_context_create($data);
            $response = file_get_contents($this->url, false, $requestContext);

            if ($response === false) {
                $error = error_get_last();
                echo "HTTP Error: " . $error['message'];
            } else {
                echo $response;
            } */
        }
    }
