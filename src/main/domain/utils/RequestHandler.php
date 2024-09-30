<?php

namespace src\main\domain\utils;

class RequestHandler {
    private string $uri;
    private string $httpMethod;
    private array $headers = [];
    private array $queryParams = [];
    private array $pathParams = [];
    private $body;

    public function __construct(){
        $this->uri = explode("?", $_SERVER["REQUEST_URI"])[0];
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];
        if (isset($_SERVER["QUERY_STRING"])){
            $this->queryParams = $this->convertQueryToArray($_SERVER["QUERY_STRING"]);
        }
        $tmpHeaders = getallheaders();
        $tmpBody = json_decode(file_get_contents("php://input"));
        if ($tmpBody) {$this->body = $tmpBody;}
        if ($tmpHeaders ){$this->headers = $tmpHeaders;}
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
    public function getBody() {return $this->body;}

    public function setUri(string $uri): void{$this->uri = $uri;}
    public function setPathParams(array $pathParams): void {$this->pathParams = $pathParams;}

}