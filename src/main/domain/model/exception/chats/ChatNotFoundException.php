<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class ChatNotFoundException extends ExceptionModel {
    public function __construct($chatId) {
        parent::__construct("Conversa não encontrada $chatId ou inexistente", 950);
    }
}