<?php
require_once 'src\main\domain\model\dto\request\HttpRequest.php';
require_once 'src\main\application\controller\Controller.php';

//TO-DO: Substituir todos os ECHO por algum registro de log

class Router {
    private static $instance = null;

    private function __construct(private array $routes) {
        foreach ($routes as $route) {
            if (!in_array(Controller::class, class_implements($route['className']))) {
                throw new InvalidArgumentException("O $route deve implementar Controller");
            }
        }
    }

    public static function getInstance(array $routes): Router {
        if (self::$instance === null) {
            self::$instance = new Router($routes);
        }
        return self::$instance;
    }

    public function redirect(HttpRequest $request) {
        $routesUris = array_keys($this->routes);
        foreach ($routesUris as $route) {   
            //echo "Controller: $route" . PHP_EOL;
            //echo "<br>Chamada: " . $request->getUri() . PHP_EOL;
            if(!str_contains($request->getUri(),     $route)) {
                //echo "<br>São diferentes" . PHP_EOL; 
                continue;}
            //echo "<br>São iguais<br>" . PHP_EOL;

            $controllerClass = $this->getRepresentationOf($this->routes[$route]['className']);
            if ($route !== "/") {
                $request->setUri(str_replace("$route", "", $request->getUri()));
            }
            return $this->getContent($controllerClass, $route, $request);
        }
        http_response_code(404);
    }

    private function getRepresentationOf(string $class): ReflectionClass {
        $instance = new ReflectionClass($class);
        if (!in_array(Controller::class, class_implements($class))) {
            throw new InvalidArgumentException("O $class deve implementar Controller");
        }
        return $instance;
    }

    private function getContent(ReflectionClass $controller, string $pathMatched, HttpRequest $request) {
        $endpoints = $this->routes[$pathMatched]['endpoints'];
        $tempStatusCode = null;

        //echo '<br>' . json_encode($endpoints);
        foreach ($endpoints as $methodName => $endpoint) {
            $controllerUri = $endpoint['uri'];
            $requestUri = $request->getUri();
            $controllerHttpMethod = $endpoint["httpMethod"];
            $requestHttpMethod = $request->getHttpMethod();
            
            //echo "<br>========<br>Controller: $controllerUri <br> Request: $requestUri" . PHP_EOL;
            $extractedPathParams = $this->extractPathParams($controllerUri, $requestUri);
            if ($extractedPathParams) {
                $request->setPathParams($extractedPathParams["pathParams"]);
                $requestUri = $extractedPathParams['request'];
                $controllerUri = $extractedPathParams['controller'];
                //echo "<br>---<br>Controller normalizado: $controllerUri <br> Request normalizado: $requestUri";
            }
            
            if ($controllerUri != $requestUri) {continue;}
            if ($controllerHttpMethod != $requestHttpMethod) {$tempStatusCode = 405; continue;}

            
            $method = $controller->getMethod($methodName);
            $attrs = $method->getAttributes();
            foreach ($attrs as $attr) {
                if ($attr->getName() == 'Authenticated') {
                    session_start();
                    if (!isset($_SESSION['user'])) {
                        http_response_code(401);
                        return;
                    }
                }
            }
            $response = $method->invoke($method->getDeclaringClass()->newInstance(), $request);
            $tempStatusCode = null;
            return $response;
        }
        if ($tempStatusCode) {http_response_code($tempStatusCode); return;}
        $fallback = $controller->getMethod("fallback");
        $response = $fallback->invoke($fallback->getDeclaringClass()->newInstance(), $request);

        return $response;
    }

    private function extractPathParams(string $controllerUri, string $requestUri): array|null {
        if (!str_contains($controllerUri, "{")) {
            //echo '<br>ERR: Endpoint não contém {<br>';
            return null;
        }

        $controllerUri = explode("/", $controllerUri);
        $requestUri = explode("/", $requestUri);
        $pathParamsArr = [];
        if (count($controllerUri) != count($requestUri)) {
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