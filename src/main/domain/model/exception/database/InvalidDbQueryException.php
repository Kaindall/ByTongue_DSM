<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

//810-820=erros de consulta
class InvalidDbQueryException extends ExceptionModel {
    public function __construct(mysqli_sql_exception $e) {
        $error_code = 812;
        if ($e->getCode() === 1054) $error_code = 813; //coluna não encontrada
        if ($e->getCode() === 1065) $error_code = 815; //erro de sintaxe na consulta
        if ($e->getCode() === 1065) $error_code = 815; //parametro null passado na a query
        if ($e->getCode() === 1146) $error_code = 814; //tabela não encontrada
        parent::__construct("Ocorreu um erro inesperado na operação do banco de dados", $error_code);
    }
}