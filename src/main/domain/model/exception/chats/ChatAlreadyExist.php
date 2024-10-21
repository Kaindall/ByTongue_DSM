<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class ChatAlreadyExist extends ExceptionModel {
    public function __construct() {
        parent::__construct("Conversa já existente com o identificador fornecido", 952);
    }
}