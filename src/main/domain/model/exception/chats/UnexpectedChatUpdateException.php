<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UnexpectedChatUpdateException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Erro não identificado no momento de atualizar a persistência da conversa já existente", 955);
    }
}