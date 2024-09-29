<?php

class HtmlResponse {
    public function __construct(private int $statusCode, private string $content) {}
    public function getStatusCode(): int {return $this->statusCode;}
    public function getContent(): string {return $this->content;}
    public function setStatusCode(int $statusCode): void {$this->statusCode=$statusCode;}
}