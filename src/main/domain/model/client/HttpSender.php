<?php 

class HttpSender {
    public function __construct(
        private array $header,
        private string $httpMethod,
        private array $body,
        private int $timeout = 60) {}
    
    public function getCurrent(): array {
        return [
            'http' => [
                'header' => $this->header,
                'method'=> $this->httpMethod,
                'content' => $this->body,
                'timeout' => $this->timeout
            ]
        ];
    }

    public function send(string $url) {}
}