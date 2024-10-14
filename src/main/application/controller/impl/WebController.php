<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';


#[HttpController('/')]
class WebController implements Controller {

    #[HttpEndpoint(uri: "/chat", method: "GET")]
    public function chat(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\chat\chat.html');
    }
    
    #[HttpEndpoint(uri: "", method: "GET")]
    public function home(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\index\index.html');
    }

    #[HttpEndpoint(uri: "/quiz", method: "GET")]
    public function quiz(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\quiz\quiz.html');
    }

    public function fallback(HttpRequest $request) {
        http_response_code(404);
        return 'Ação de fallback do WebController';
    }
}