<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class InvalidIdentifierException extends ExceptionModel {
    public function __construct() {
        parent::__construct("O formato do ID passado é inválido", 953);
    }
}