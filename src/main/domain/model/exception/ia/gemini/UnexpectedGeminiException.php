<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UnexpectedGeminiException extends ExceptionModel {
    public function __construct($response) {
        parent::__construct("Erro inesperado ao consultar a IA: $response", 912);
    }
}