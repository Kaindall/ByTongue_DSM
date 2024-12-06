<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class EmptyNameException extends ExceptionModel {
    public function __construct() {
        parent::__construct("O campo nome {name} precisa ser preenchido", 854);
    }
}