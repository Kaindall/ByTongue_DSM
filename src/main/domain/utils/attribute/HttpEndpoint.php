<?php

#[Attribute]
class HttpEndpoint {
    public function __construct(
        public string $uri,
        public string $method
    ) {}
}