<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class ObjectNotQuizzeableException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Objeto inválido para gerar questões", error_code: 600);
    }
}