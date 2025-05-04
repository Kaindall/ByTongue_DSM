<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UserExistsException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Usuário já existente", 851);
    }
}