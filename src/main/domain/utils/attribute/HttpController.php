<?php

#[Attribute]
class HttpController {  
    private static array $routes = [];
    
    public function __construct(public string $path) {
    }
}