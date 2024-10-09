<?php

enum CurlHttpMethod: string {
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';

    public function curlOpt() {
        return match($this) {
            self::GET => CURLOPT_HTTPGET,
            self::POST => CURLOPT_POST,
            self::PUT => CURLOPT_CUSTOMREQUEST,
            self::PATCH => CURLOPT_CUSTOMREQUEST,
            self::DELETE => CURLOPT_CUSTOMREQUEST
        };
    }

    public function curlValue() {
        return match($this) {
            self::GET => true,
            self::POST => true,
            self::PUT => 'PUT',
            self::PATCH => 'PATCH',
            self::DELETE => 'DELETE',
        };
    }
}