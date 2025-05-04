<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

//800-809: erros de conexão
class InvalidDbConnectionException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Erro de conexão com o banco de dados", 801);
    }
}