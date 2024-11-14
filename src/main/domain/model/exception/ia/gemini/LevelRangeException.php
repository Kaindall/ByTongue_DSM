<?php
require_once 'src\main\domain\model\exception\ExceptionModel.php';

class LevelRangeException extends ExceptionModel {
    public function __construct() {
        parent::__construct("Nível de conhecimento no idioma inválido", 900);
    }
}