<?php

class HttpSenderBuilder {
    private array $headers = [];
    private CurlHttpMethod $httpMethod;
    private string | array $body;
    private int $timeout;

    public function withHeaders(array $headers): self {
        $this->headers = $headers;
        return $this;
    }

    public function withHttpMethod(string $httpMethod): self {
        $httpMethod = trim(strtoupper($httpMethod));
        $curlHttpMethod = CurlHttpMethod::from($httpMethod);
        $this->httpMethod = $curlHttpMethod;
        return $this;
    }

    public function withBody(string | array $body): self {
        $this->body = $body;
        return $this;
    }

    public function withTimeout(int $timeout): self { 
        $this->timeout = $timeout;
        return $this;
    }

    public function build(): HttpSender { 
        return new HttpSender($this->headers, 
            $this->httpMethod, 
            $this->body,
            $this->timeout ?? 60);
    }
}