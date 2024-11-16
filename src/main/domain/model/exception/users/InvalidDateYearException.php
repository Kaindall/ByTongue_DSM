<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class InvalidDateYearException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Insira um ano válido para ser atualizado", 858);
    }
}