<?php

#[HttpController("/auth")]
class AuthController implements Controller {
    private SessionService $sessionService;

    public function __construct() {
        $this->sessionService = new SessionService();
    }

    #[HttpEndpoint(uri: "", method: "POST")]
    public function login(HttpRequest $request) {
        header("Content-Type: application/json");
        try {
            $this->sessionService->start($request->getBody());
            http_response_code(200);
            return;
        } catch (InvalidDbQueryException | InvalidDbConnectionException $e) {
            http_response_code(500);
            return $e;
        } catch (InvalidAuthException $e) {
            http_response_code(401);
            return;
        }
    }

    #[HttpEndpoint(uri: "", method: "GET")]
    public function getSession(HttpRequest $request) {
        header("Content-Type: application/json");
        $result = $this->sessionService->retrieve();
        if (!$result) {http_response_code(404); return;}
        http_response_code(200);
        return $result;
    }

    #[HttpEndpoint(uri: "", method: "DELETE")]
    public function logout(HttpRequest $request): void {
        header("Content-Type: application/json");
        $result = $this->sessionService->remove();
        if (!$result) {http_response_code(404); return;}
        http_response_code(200);
        return;
    }

    public function fallback(HttpRequest $request) {}
}