<?php
require_once 'src\main\domain\utils\RequestHandler.php';
use src\main\domain\utils\RequestHandler;


interface Controller {
    public function fallback(RequestHandler $request);
}