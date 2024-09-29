<?php

class ControllerException extends Exception {
    private string $date;

    private function __construct(private string $message, private int $code = 0) {
        $this->date = date("d/m/o H:i:s (P)");
    }

    
}