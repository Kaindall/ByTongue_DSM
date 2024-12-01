<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class SessionExistsException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Já existe uma sessão em andamento", 891);
    }
}