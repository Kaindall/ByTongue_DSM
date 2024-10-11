<?php 

class HttpClient {
    public function __construct(
        private array $header,
        private CurlHttpMethod $httpMethod,
        private array $body,
        private int $timeout = 60) {}

    public function executeRequest(string $url) {
        ini_set('allow_url_fopen', '1');
        $req = curl_init($url);
            curl_setopt($req, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($req, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($req, $this->httpMethod->curlOpt(), $this->httpMethod->curlValue());
            if ($this->body) {
                curl_setopt($req, CURLOPT_POSTFIELDS, json_encode($this->body));
            } 
            curl_setopt($req, CURLOPT_HTTPHEADER, $this->header);

            $response = curl_exec($req);

            if ($response === false) {
                $error = curl_error($req);
                return $error;
            } else {
                return $response;
            }
    }
}