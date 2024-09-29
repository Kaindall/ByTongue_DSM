<?php

require_once 'src\main\application\controller\Controller.php';

class ChatController implements Controller {
    
    #[HttpReceiver(uri: "/teste", method: "GET")]
    public function send() {
        return "Funcionou no Controller";
    }

    public function fallback() {
        return 'Ação de fallback do ChatController';
    }
}