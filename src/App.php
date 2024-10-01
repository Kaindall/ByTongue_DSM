<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\router\Router.php';
require_once 'src\AppConfig.php';

use src\main\domain\utils\RequestHandler;

class App {
    public function __construct(?array $args = []) {
        $request = new RequestHandler();

        ob_start();
        $response = Router::getInstance($args['routes'])->redirect($request);
        if ($response != null) {echo $response;}
        ob_end_flush();
    }
}

new App(AppConfig::bootstrap());