<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class LinkNotFoundException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Registro de conexão entre usuário e chat não encontrado", 860);
    }
}