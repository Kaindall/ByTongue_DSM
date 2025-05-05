<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class InvalidAuthException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Email não encontrado ou não coincide com a senha informada", 890);
    }
}