<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class ChatAlreadyExistException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Ocorreu um erro ao gerar a conversa", 952);
    }
}