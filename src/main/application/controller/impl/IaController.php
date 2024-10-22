<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\infrastructure\client\GeminiClient.php';
require_once 'src\main\domain\model\exception\ia\ObjectNotQuizzeable.php';


#[HttpController("/ias")]
class IaController implements Controller {
    private ChatService $chatService;

    //TODO: Injeção de dependência do ChatService
    public function __construct() {
        $this->chatService = new ChatService(new ChatRepository(), new GeminiService());
    }

    #[HttpEndpoint(uri: "/chat", method: "POST")]
    public function createChat(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $response = $this->chatService->postMessage(null, $request->getBody(), $request->getQueryParams());
            http_response_code(200);
            return $response;
        } catch (EmptyBodyException | InvalidBodyException $e) {
            http_response_code(400);
            header("Content-Type: application/json");
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         } catch (UnexpectedGeminiException $e)  {
            http_response_code(500);
            header("Content-Type: application/json");
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         }
    }

    #[HttpEndpoint(uri: "/chat/{id}", method: "GET")]
    public function findChat(HttpRequest $request) {
        try {
            $response = $this->chatService->findChat($request->getPathParams()['id']);
            http_response_code(200);
            header("Content-Type: application/json");
            return $response;
        } catch (ChatNotFound $e) {
            http_response_code(404);
            return;
        }
    }

    #[HttpEndpoint(uri: "/chat/{id}", method: "POST")]
    public function appendMessage(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $response = $this->chatService->postMessage($request->getPathParams()['id'], $request->getBody(), null);
            http_response_code(200);
            return $response;
        } catch (EmptyBodyException | InvalidBodyException | ChatNotFound $e) {
            http_response_code(400);
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         } catch (UnexpectedGeminiException $e)  {
            http_response_code(500);
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         }
    }

    #[HttpEndpoint(uri: "/chat/{id}", method: 'DELETE')]
    public function deleteChat(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $response = $this->chatService->deleteChat($request->getPathParams()['id']);
            if ($response) {http_response_code(200);
            } else {http_response_code(400);}
            return;
        } catch (ChatNotFound $e) {
            http_response_code(400);
            return json_encode($e->toResponse(), JSON_PRETTY_PRINT);
         }
    }

    #[HttpEndpoint(uri: "/quiz", method: "GET")]
    public function createQuiz(HttpRequest $request) {
        try {
            $iaService = new GeminiService();
            $response = $iaService->retrieveQuiz($request->getQueryParams());
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