<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';

class ChatController implements Controller {
    
    #[HttpReceiver(uri: "/teste", method: "GET")]
    public function send($requestInfo) {
        return "Funcionou no Controller";
    }

    public function fallback($request) {
        return 'Ação de fallback do ChatController';
    }
}