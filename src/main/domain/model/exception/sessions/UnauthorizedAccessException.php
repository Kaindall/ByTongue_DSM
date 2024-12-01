<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UnauthorizedAccessException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Acesso negado a este usuário para checar as informações solicitadas", 892);
    }
}