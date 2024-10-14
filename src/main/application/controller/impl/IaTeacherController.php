<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';

use src\main\domain\utils\RequestHandler;

#[HttpReceiver("/apis/ias/teacher")]
class IaTeacherController implements Controller {
    
    #[HttpEndpoint(uri: "", method: "GET")]
    public function postTeste(RequestHandler $request) {
        http_response_code(200);
        header("Content-Type: application/json");
        sleep(3);
        if ($request->getBody()) {return json_encode($request->getBody());}
        return;
    }

    #[HttpEndpoint(uri: "/teste/{id}/something", method: "GET")]
    public function send(RequestHandler $request) {
        return "<br>Funcionou no Controller";
    }

    public function fallback(RequestHandler $request) {
        http_response_code(400);
        return;
    }
}