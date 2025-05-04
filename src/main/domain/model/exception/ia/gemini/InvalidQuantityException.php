<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class InvalidQuantityException extends ExceptionModel {
    public function __construct() {
        parent::__construct("A quantidade de perguntas precisa ser um valor numérico válido", 903);
    }
}