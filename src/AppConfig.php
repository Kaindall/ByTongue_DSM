<?php

abstract class AppConfig {
    public static function bootstrap(): array {
        $args = [];
        $args['routes'] = self::defineRoutes();
        return $args;
    }

    private static function defineRoutes(): array {
        $routes = [];
        $directory = 'src/main';  
        $directoryIterator = new RecursiveDirectoryIterator($directory);
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
            $attr = $reflectedClass->getAttributes('HttpReceiver');
            if (empty($attr)) {continue;}
            $uri = $attr[0]->getArguments()[0];
            
            $routes[$uri] = $fileName;
            return $routes;
        }
    }
}