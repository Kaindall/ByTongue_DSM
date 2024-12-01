<?php
require_once 'src/main/domain/model/exception/ExceptionModel.php';

class EmptyDataException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Insira valores válidos para serem atualizados", 855);
    }
}