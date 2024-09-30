<?php

#[Attribute]
class HttpReceiver {
    public function __construct(
        public string $uri,
        public string $method
    ) {}
}