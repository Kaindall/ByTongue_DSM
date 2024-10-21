<?php
require_once 'src\main\domain\model\request\HttpRequest.php';
require_once 'src\main\infrastructure\config\Router.php';
require_once 'src\AppConfig.php';


class App {
    public function __construct(?array $args = []) {
        $request = new HttpRequest();

        ob_start();
        $response = Router::getInstance($args['routes'])->redirect($request);
        if ($response != null) {echo $response;}
        ob_end_flush();
    }
}

new App(AppConfig::bootstrap());