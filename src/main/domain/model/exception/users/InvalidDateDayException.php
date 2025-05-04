<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class InvalidDateDayException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Insira um dia válido para ser atualizado", 856);
    }
}