<?php
require_once 'src\main\domain\model\request\RequestHandler.php';
require_once 'src\main\application\api\controller\ChatController.php';

use src\main\domain\model\request\RequestHandler;

class Router {
    
    public static function call() {
        $request = new RequestHandler();
        $controller = new ReflectionClass(ChatController::class);
        $methods = $controller->getMethods();

        foreach ($methods as $method) {
            $attributes = $method->getAttributes();

            foreach ($attributes as $attribute) {
                $controllerUri = explode("/", $attribute->getArguments()["uri"]);
                $controllerHttpMethod = $attribute->getArguments()["method"];

                $requestUri = explode("/", $request->getUri());
                $requestHttpMethod = $request->getHttpMethod();

                if ($controllerUri != $requestUri || $controllerHttpMethod != $requestHttpMethod) {continue;}

                
                return $method->invoke($method->getDeclaringClass()->newInstance());
            }
        }
    }
}

echo Router::call();