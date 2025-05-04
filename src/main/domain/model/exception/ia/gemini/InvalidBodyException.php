<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class InvalidBodyException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Corpo da requisição inválido", 911);
    }
}