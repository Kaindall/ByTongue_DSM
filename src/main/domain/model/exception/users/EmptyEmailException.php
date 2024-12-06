<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class EmptyEmailException extends ExceptionModel {
    public function __construct() {
        parent::__construct("O campo de email precisa ser preenchido", 852);
    }
}