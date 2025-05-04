<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class UserNotFoundException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Usuário não encontrado", 850);
    }
}