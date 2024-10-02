<?php

abstract class AppConfig {
    public static function bootstrap(): array {
        $args = [];
        $args['routes'] = self::defineConfigs();
        return $args;
    }

    private static function defineConfigs(): array {
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
                if ($attr->getName() == 'HttpReceiver') {
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
}