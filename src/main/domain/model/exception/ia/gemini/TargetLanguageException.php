<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class TargetLanguageException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Idioma de destino informado é inválido", 902);
    }
}