<?php
require_once 'src\main\domain\model\request\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\application\controller\impl\ChatController.php';
require_once 'src\main\application\controller\impl\WebController.php';

use src\main\domain\model\request\RequestHandler;

class Router {
    private static $instance = null;

    private function __construct(private array $routes) {
        foreach ($routes as $route) {
            if (!in_array(Controller::class, class_implements($route))) {
                throw new InvalidArgumentException("O $route deve implementar Controller");
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
        if (!str_starts_with($request->getUri(), "/apis")) {
            $webController = $this->getRepresentationOf('WebController');
            $response = $this->getContent($webController, $request);
            
            return $response;
        };

        $requestUri = $request->getUri();
        $routesUris = array_keys($this->routes);
        echo "<br><br>Rotas: ";
        var_dump($routesUris);
        foreach ($routesUris as $route) {   
            echo "<br><br>Controller: $route";
            echo "<br>Chamada: $requestUri";
            if(!str_contains($request->getUri(), $route)) {echo "<br>São diferentes"; continue;}
            echo "<br>São iguais<br><br>";

            $controllerRepresentation = $this->getRepresentationOf($this->routes[$route]);
            return $this->getContent($controllerRepresentation, $request);
        }
        //return $this->routes;
    }

    private function getRepresentationOf(string $class): ReflectionClass {
        $instance = new ReflectionClass($class);
        if (!in_array(Controller::class, class_implements($class))) {
            throw new InvalidArgumentException("O $class deve implementar Controller");
        }
        return $instance;
    }

    private function getContent(ReflectionClass $controller, RequestHandler $request) {
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

                if ($controllerUri != $requestUri || $controllerHttpMethod != $requestHttpMethod) {echo '<br>São diferentes';continue;}
                
                echo "<br>São similares";

                $response = $method->invoke($method->getDeclaringClass()->newInstance());
                return $response;
            }
        }
        $fallback = $controller->getMethod("fallback");
        $response = $fallback->invoke($fallback->getDeclaringClass()->newInstance());

        return $response;
    }
}