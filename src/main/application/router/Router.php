<?php
require_once 'src\main\domain\model\request\RequestHandler.php';
require_once 'src\main\application\api\controller\Controller.php';
require_once 'src\main\application\api\controller\impl\ChatController.php';
require_once 'src\main\application\api\controller\impl\WebController.php';

use src\main\domain\model\request\RequestHandler;

class Router {
    private static $instance = null;

    private function __construct(private array $routes) {
        foreach ($routes as $route) {
            if (!in_array(Controller::class, class_implements($route))) {
                throw new InvalidArgumentException("O $route deve implementar Controller:class");
            }
        }
    }

    public static function getInstance(): Router {
        if (self::$instance === null) {
            self::$instance = new Router(require 'src/resources/routes/Routes.php');
        }
        return self::$instance;
    }

    public function redirect(RequestHandler $request) {
        $requestUri = $request->getUri();
        $routesUris = array_keys($this->routes);

        if (!str_starts_with($request->getUri(), "/api")) {
            $webController = new WebController();
            echo "' $requestUri ' não começa com /api, redirecionando para o WebController";
            $response = $this->getContent($webController, $request);
            if (!$response) {
                echo 'Nenhuma resposta disponível';
                $response = $webController->fallback();
            }
            return $response;
        };

        foreach ($routesUris as $route) {
            echo "$requestUri vs $route<br><br>";
            
            if(!str_contains($request->getUri(), $route)) {continue;};
            echo "<br><br>os path são iguais!";

        }
        return $this->routes;
    }

    private function getContent(Controller $controller, RequestHandler $request) {
        $controller = new ReflectionClass($controller);
        $methods = $controller->getMethods();
        
        echo "<br><br>Iterando sobre o $controller";
        foreach ($methods as $method) {
            $attributes = $method->getAttributes();

            foreach ($attributes as $attribute) {
                $controllerUri = array_filter(explode("/", $attribute->getArguments()["uri"]));
                $tempCtrlUri = $attribute->getArguments()["uri"];
                $controllerHttpMethod = $attribute->getArguments()["method"];
                
                $requestUri = array_filter(explode("/",  $request->getUri()));
                $tempUriReq = $request->getUri();
                $requestHttpMethod = $request->getHttpMethod();
                
                echo "<br><br>Controller: $tempCtrlUri <br> Request: $tempUriReq";

                if ($controllerUri != $requestUri || $controllerHttpMethod != $requestHttpMethod) {echo '= São diferentes';continue;}
                
                echo "<br>São similares";

                $response = $method->invoke($method->getDeclaringClass()->newInstance());
                return $response;
            }
        }
    }
}