<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class OriginLanguageException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Idioma de origem informado é inválido", 901);
    }
}