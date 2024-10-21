<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class ChatNotFound extends ExceptionModel {
    public function __construct() {
        parent::__construct("Conversa não encontrado ou inexistente", 950);
    }
}