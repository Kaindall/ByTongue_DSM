<?php
require_once 'src\main\domain\model\request\HttpRequest.php';


interface Controller {
    public function fallback(HttpRequest $request);
}