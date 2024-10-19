<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\infrastructure\client\GeminiClient.php';
require_once 'src\main\application\controller\exception\ObjectNotQuizzeable.php';


#[HttpController("/ias")]
class IaController implements Controller {
    
    #[HttpEndpoint(uri: "/chat", method: "POST")]
    public function postTeste(HttpRequest $request) {
        $client = new GeminiService();
        header("Content-Type: application/json");

        try {
            $response = $client->retrieveResult($request->getBody());
            http_response_code(200);
            return $response;

        } catch (EmptyBodyException | InvalidBodyException $e) {
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         }
    }

    #[HttpEndpoint(uri: "/chat/{id}", method: "GET")]
    public function findChat(HttpRequest $request) {
        return "<br>Funcionou no Controller";
    }

    #[HttpEndpoint(uri: "/quiz", method: "GET")]
    public function send(HttpRequest $request) {
        try {
            $quizClient = new GeminiService();
            if (!($quizClient instanceof Quizzeable)) {throw new ObjectNotQuizzeable();}

            $response = $quizClient->retrieveQuiz($request->getQueryParams());
            http_response_code(200);
            header("Content-Type: application/json");
            return $response;

        } catch (LevelRangeException | OriginLanguageException | TargetLanguageException | InvalidQuantityException $e) {
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);

        } catch (ObjectNotQuizzeable $e ) {
            http_response_code(response_code: 500);
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