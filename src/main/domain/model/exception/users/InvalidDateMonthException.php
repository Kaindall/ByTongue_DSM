<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class InvalidDateMonthException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Insira um mês válido para ser atualizado", 857);
    }
}