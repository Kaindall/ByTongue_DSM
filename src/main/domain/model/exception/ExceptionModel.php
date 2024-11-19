<?php

abstract class ExceptionModel extends Exception {
    private $timestamp;
    public function __construct(protected $message, private $error_code) {
        parent::__construct($message, $error_code);
        $date = new DateTime("now", new DateTimeZone(date_default_timezone_get()));
        $this->timestamp = $date->format("d/m/Y H:i:s (P \U\T\C)");
    }

    public function __toString(): string {
        return json_encode([
            'error_code' => $this->getCode(),
            'message' => $this->getMessage(),
            'timestamp' => $this->timestamp
        ], JSON_PRETTY_PRINT);
    }
}