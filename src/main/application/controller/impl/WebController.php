<?php

require_once 'src\main\application\controller\Controller.php';

class WebController implements Controller {

    #[HttpReceiver(uri: "/chat", method: "GET")]
    public function chat() {
        return file_get_contents('src\main\application\web\view\chat\chat.html');
    }
    
    #[HttpReceiver(uri: "/", method: "GET")]
    public function home() {
        return file_get_contents('src\main\application\web\view\index\index.html');
    }

    public function fallback() {
        return 'Ação de fallback do WebController';
    }
}