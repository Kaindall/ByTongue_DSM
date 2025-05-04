<?php
require_once 'src/main/domain/model/dto/request/HttpRequest.php';
require_once 'src/main/infrastructure/config/Router.php';
require_once 'src/AppConfig.php';

class App {
    public function __construct(?array $args = []) {
        $request = new HttpRequest();
        session_start();
        ob_start();
        $response = Router::getInstance($args['routes'])->redirect($request);
        if ($response != null) {echo $response;}
        ob_end_flush();
    }
}

new App(AppConfig::bootstrap());