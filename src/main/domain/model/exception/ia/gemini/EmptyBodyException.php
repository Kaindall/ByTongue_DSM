<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class EmptyBodyException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Nenhuma mensagem informada", 910);
    }
}