<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';

use src\main\domain\utils\RequestHandler;

class IaTeacherController implements Controller {
    
    #[HttpReceiver(uri: "", method: "GET")]
    public function test(RequestHandler $request) {
        http_response_code(200);
        header("Content-Type: application/json");
        sleep(3);
        return json_encode($request->getBody());
    }

    #[HttpReceiver(uri: "/teste/{id}/something", method: "GET")]
    public function send(RequestHandler $request) {
        return "Funcionou no Controller";
    }

    public function fallback(RequestHandler $request) {
        return 'Ação de fallback do ChatController';
    }
}