<?php

namespace src\main\domain\model\request;

class RequestHandler {
    private string $uri;
    private string $httpMethod;
    private array $queryParams = [];

    private array $pathParams = [];

    public function __construct(){
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];
        $this->queryParams = $this->convertQueryToArray($_SERVER["QUERY_STRING"]);
    }

    private function convertQueryToArray(string $queryParams): array {
        $queriesArr = explode('&', $queryParams);
        $queriesDefinitiveArr = [];
        foreach ($queriesArr as $query){
            $queryArr = explode('=', $query);
            $key = trim($queryArr[0]);
            $value = trim($queryArr[1]);
            $queriesDefinitiveArr[$key] = $value;
        }
        return $queriesDefinitiveArr;
    }

    public function getUri(): string {return $this->uri;}
    public function getHttpMethod(): string {return $this->httpMethod;}
    public function getQueryParams(): array {return $this->queryParams;}
    public function getPathParams(): array {return $this->pathParams;}

    public function setUri(string $uri): void{$this->uri = $uri;}



}