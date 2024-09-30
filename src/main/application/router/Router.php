<?php
require_once 'src\main\domain\utils\RequestHandler.php';
require_once 'src\main\application\controller\Controller.php';
require_once 'src\main\application\controller\impl\IaTeacherController.php';
require_once 'src\main\application\controller\impl\WebController.php';

use src\main\domain\utils\RequestHandler;

//TO-DO: Substituir todos os ECHO por algum registro de log

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
        foreach ($routesUris as $route) {   
            //echo "Controller: $route";
            //echo "<br>Chamada: $requestUri";
            if(!str_contains($request->getUri(), $route)) {/* echo "<br>São diferentes" */; continue;}
            //echo "<br>São iguais<br>";

            $controllerClass = $this->getRepresentationOf($this->routes[$route]);
            $request->setUri(str_replace("$route", "", $request->getUri()));
            return $this->getContent($controllerClass, $request);
        }
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
        $tempStatusCode = null;

        foreach ($methods as $method) {
            $response = $this->findHttpReceiver($method, $request);
            if ($response && isset($response["content"])) {return $response["content"];}
            if ($response && isset($response['forceStatusCode'])) {$tempStatusCode = $response['forceStatusCode'];}
            
        }
        if ($tempStatusCode) {http_response_code(405); return;}
        //echo "<br>==========";
        $fallback = $controller->getMethod("fallback");
        $response = $fallback->invoke($fallback->getDeclaringClass()->newInstance(), $request);

        return $response;
    }
    private function findHttpReceiver(ReflectionMethod $method, RequestHandler $request): array|null {
        $attributes = $method->getAttributes();
        $forceStatusCode = null;

        foreach ($attributes as $attribute) {
            $controllerUri = $attribute->getArguments()["uri"];
            $requestUri = $request->getUri();
            $controllerHttpMethod = $attribute->getArguments()["method"];
            $requestHttpMethod = $request->getHttpMethod();
            
            //echo "<br>========<br>Controller: $controllerUri <br> Request: $requestUri";
            $extractedPathParams = $this->extractPathParams($controllerUri, $requestUri);
            if ($extractedPathParams) {
                $request->setPathParams($extractedPathParams["pathParams"]);
                $requestUri = $extractedPathParams['request'];
                $controllerUri = $extractedPathParams['controller'];
            }

            if ($controllerUri != $requestUri) {/* echo '<br>São diferentes' */;continue;}
            if ($controllerHttpMethod != $requestHttpMethod) {$forceStatusCode = 405; continue;}
            
            $response = $method->invoke($method->getDeclaringClass()->newInstance(), $request);

            return array("content" => $response);
        }
        if ($forceStatusCode) {return array("forceStatusCode" =>$forceStatusCode);}
        return null;
    }

    private function extractPathParams(string $controllerUri, string $requestUri): array|null {
        if (!str_contains($controllerUri, "{")) {
            return null;
        }

        $controllerUri = explode("/", $controllerUri);
        $requestUri = explode("/", $requestUri);
        $pathParamsArr = [];
        if (!(array_count_values($controllerUri) == array_count_values($requestUri))) {
            return null;
        }

        foreach ($controllerUri as $index => $uriPart) {
            if (!str_contains($uriPart, "{")) {continue;}
            $controllerUri = str_replace($uriPart, "...", $controllerUri);
            $pathParamsArr[trim($uriPart, "{}")] = $requestUri[$index];
            $requestUri[$index] = "...";
        }
        $requestUri = implode("/", $requestUri);
        $controllerUri = implode("/", $controllerUri);

        $response = array(
            "request" => $requestUri,
            "controller" => $controllerUri,
            "pathParams"=> $pathParamsArr
        );
        return $response;
    }
}