<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class EmptyPasswordException extends ExceptionModel {
    public function __construct() {
        parent::__construct("O campo de senha {password} precisa ser preenchido", 853);
    }
}