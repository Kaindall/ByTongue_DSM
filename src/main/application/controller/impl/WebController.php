<?php
require_once 'src/main/domain/model/dto/request/HttpRequest.php';
require_once 'src/main/application/controller/Controller.php';


#[HttpController('/')]
class WebController implements Controller {

    #[HttpEndpoint(uri: "/chat", method: "GET")]
    public function chat(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src/main/application/web/view/chat/chat.html');
    }
    
    #[HttpEndpoint(uri: "/", method: "GET")]
    public function home(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src/main/application/web/view/index/index.html');
    }

    #[HttpEndpoint(uri: "/quiz", method: "GET")]
    public function quiz(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src/main/application/web/view/quiz/quiz.html');
    }

    #[HttpEndpoint(uri: "/login", method: "GET")]
    public function login(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\login\login.html');
    }

    #[HttpEndpoint(uri: "/resetPassword", method: "GET")]
    public function resetPassword(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\profile\resetPassword.html');
    }

    #[HttpEndpoint(uri: "/signup", method: "GET")]
    public function signup(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\signup\signup.html');
    }

    #[HttpEndpoint(uri: "/aboutUs", method: "GET")]
    public function aboutUs(HttpRequest $request) {
        http_response_code(200);
        return file_get_contents('src\main\application\web\view\aboutUs\about-us.html');
    }

    #[HttpEndpoint(uri: "/settings", method: "GET")]
    public function settings(HttpRequest $request) {
        http_response_code(200);
        
        return phpinfo();
    }
    public function fallback(HttpRequest $request) {
        http_response_code(404);
        return 'Ação de fallback do WebController';
    }
}