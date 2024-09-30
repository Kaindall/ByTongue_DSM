<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';

use src\main\domain\utils\RequestHandler;

class ChatController implements Controller {
    
    #[HttpReceiver(uri: "/teste", method: "GET")]
    public function test(RequestHandler $request) {
        return "Funcionou no Controller";
    }

    #[HttpReceiver(uri: "/teste/{id}/something", method: "GET")]
    public function send(RequestHandler $request) {
        return "Funcionou no Controller";
    }

    public function fallback(RequestHandler $request) {
        return 'Ação de fallback do ChatController';
    }
}