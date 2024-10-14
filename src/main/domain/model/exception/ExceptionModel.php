<?php

abstract class ExceptionModel extends Exception {
    private $timestamp;
    public function __construct(protected $message, private $error_code) {
        parent::__construct($message, $error_code);
        $this->timestamp = date("d/m/o H:i:s (P)");
    }

    public function toResponse(): array
    {
        return [
            'error_code' => $this->getCode(),
            'message' => $this->getMessage(),
            'timestamp' => $this->timestamp
        ];
    }
}