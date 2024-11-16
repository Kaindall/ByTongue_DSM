<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class InvalidBirthdayFormatException extends ExceptionModel {
    public function __construct() {
        parent::__construct("A formatação do campo da data de nascimento {birthday} está em um formato inválido. Tente yyyy/mm/dd", 859);
    }
}