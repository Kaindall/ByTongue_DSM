<?php

namespace src\main\domain\model\request;

class RequestHandler {
    private string $uri;
    private string $httpMethod;
    private ?string $query_params;

    public function __construct(){
        $this->uri = $_SERVER["REQUEST_URI"];
        $this->httpMethod = $_SERVER["REQUEST_METHOD"];
        $this->query_params = $_SERVER["QUERY_STRING"] ?? null;
    }

    public function getUri(){return $this->uri;}
    public function getHttpMethod(){return $this->httpMethod;}
    public function getQueryParams(){return $this->query_params;}

}