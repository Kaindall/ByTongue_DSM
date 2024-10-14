<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\infrastructure\client\GeminiClient.php';


#[HttpController("/ias")]
class IaController implements Controller {
    
    #[HttpEndpoint(uri: "/chat", method: "POST")]
    public function postTeste(HttpRequest $request) {
        header("Content-Type: application/json");
        $key = getenv("GEMINI_KEY");
        $client = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$key");
        $response = $client->sendMessage($request->getBody());
        http_response_code(200);
        return $response;
    }

    #[HttpEndpoint(uri: "/chat/{id}", method: "GET")]
    public function findChat(HttpRequest $request) {
        return "<br>Funcionou no Controller";
    }

    #[HttpEndpoint(uri: "/quiz", method: "GET")]
    public function send(HttpRequest $request) {
        $key = getenv("GEMINI_KEY");
        try {
            $client = new GeminiClient("https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=$key");
            $response = $client->getQuestions($request->getQueryParams());
            http_response_code(200);
            header("Content-Type: application/json");
            return $response;
        } catch (LevelRangeException | OriginLanguageException | TargetLanguageException $e) {
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
        }
    }

    public function fallback(HttpRequest $request) {
        echo 'Fallback do IaController';
        http_response_code(400);
        return;
    }
}