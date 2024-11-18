<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class UnexpectedChatDeletionException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Erro não identificado no momento de remover a persistência da conversa", 954);
    }
}