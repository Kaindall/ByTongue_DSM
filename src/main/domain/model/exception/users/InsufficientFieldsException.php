<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class InsufficientFieldsException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Insira campos válidos para atualizar o usuário", 856);
    }
}