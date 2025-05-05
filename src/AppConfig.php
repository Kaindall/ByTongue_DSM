<?php

abstract class AppConfig {
    static private $args = [];

    public static function bootstrap(): array {
        if (empty(self::$args)) {
            self::$args['routes'] = self::defineRoutes();
        }
        self::loadEnv();
        return self::$args;
    }

    private static function defineRoutes(): array {
        $routes = [];
        $directory = 'src/main';  
        $directoryIterator = new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($directoryIterator);
        
        foreach ($iterator as $file) {
            if ($file->isDir() || 
                !str_ends_with($file->getFilename(), ".php") ||
                str_contains(strtolower($file->getFilename()), "exception")) {continue;}
            $filePath = str_replace('\\',"/", str_replace("__construct", "", $file->getPathname()));
            $fileName = str_replace('.php', '',$file->getFilename());
        
            require_once $filePath;
            if (!class_exists($fileName)) {continue;}

            $reflectedClass = new ReflectionClass($fileName);
            $attrs = $reflectedClass->getAttributes();
            if (empty($attrs)) {continue;}

            foreach ($attrs as $attr) {
                if ($attr->getName() == 'HttpController') {
                    $uri = $attr->getArguments()[0];
                    $methods = $reflectedClass->getMethods();
                    if (empty($methods)) {continue;}

                    $endpoints = [];
                    $routes[$uri] = array('className' => $fileName);
                    
                    foreach ($methods as $method) {
                        $methodAttr = $method->getAttributes('HttpEndpoint');
                        if (empty($methodAttr)) {continue;}
                        $methodUri = $methodAttr[0]->getArguments()['uri'];
                        $httpMethod = $methodAttr[0]->getArguments()['method'];

                        $endpoints = array_merge($endpoints, 
                            array($method->getName() => array('uri' => $methodUri, 'httpMethod'=>$httpMethod)));
                    }
                    if (!empty($endpoints)) {
                        $routes[$uri]['endpoints'] = $endpoints;
                    }
                }
            }
        }
        return $routes;
    }

    private static function loadEnv() {
        $file = '.env';

        if (file_exists($file)) {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0 || trim($line) === '') {
                    continue;
                }
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value, '"');
                
                // Define apenas se a variável ainda não foi definida no ambiente
                if (getenv($name) === false) {
                    putenv("$name=$value");
                }
            }
        }
    }
}