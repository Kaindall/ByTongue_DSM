<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class InvalidChatObjectException extends ExceptionModel {
    public function __construct() {
        parent::__construct("O objeto a ser criado precisa ser uma entidade Chat", 951);
    }
}