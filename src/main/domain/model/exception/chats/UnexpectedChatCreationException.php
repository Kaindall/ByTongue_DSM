<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UnexpectedChatCreationException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Erro não identificado no momento de persistir a conversa", 954);
    }
}