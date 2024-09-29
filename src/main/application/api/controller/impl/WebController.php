<?php

require_once 'src\main\application\api\controller\Controller.php';

class WebController implements Controller {
    
    #[HttpReceiver(uri: "/", method: "GET")]
    public function send() {
        return file_get_contents('src\main\application\web\view\index\index.html');
    }

    public function fallback() {
        return 'Ação de fallback do WebController';
    }
}