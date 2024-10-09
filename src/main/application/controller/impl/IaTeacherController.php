<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\infrastructure\client\GeminiClient.php';


#[HttpController("/ias/teacher")]
class IaTeacherController implements Controller {
    
    #[HttpEndpoint(uri: "", method: "POST")]
    public function postTeste(HttpRequest $request) {
        header("Content-Type: application/json");
        
        $client = new GeminiClient('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=AIzaSyA9nRcb9Bq8vzyRooksJnYc5lAfcN49qTY');
        $response = $client->sendMessage($request->getBody());
        http_response_code(200);
        return $response;
    }

    #[HttpEndpoint(uri: "/{id}", method: "GET")]
    public function send(HttpRequest $request) {
        return "<br>Funcionou no Controller";
    }

    public function fallback(HttpRequest $request) {
        http_response_code(400);
        return;
    }
}