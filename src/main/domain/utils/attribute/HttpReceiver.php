<?php

#[Attribute]
class HttpReceiver {  
    private static array $routes = [];
    
    public function __construct(public string $path) {
    }
}