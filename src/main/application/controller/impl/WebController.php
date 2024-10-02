<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';

use src\main\domain\utils\RequestHandler;
#[HttpReceiver('')]
class WebController implements Controller {

    #[HttpEndpoint(uri: "/chat", method: "GET")]
    public function chat(RequestHandler $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\chat\chat.html');
    }
    
    #[HttpEndpoint(uri: "/", method: "GET")]
    public function home(RequestHandler $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\index\index.html');
    }

    public function fallback(RequestHandler $request) {
        http_response_code(404);
        return 'Ação de fallback do WebController';
    }
}