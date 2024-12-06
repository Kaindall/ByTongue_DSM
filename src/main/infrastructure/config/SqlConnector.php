<?php

class SqlConnector {
    private static $instance;

    private function __construct() {}

    public static function getInstance(): mysqli {
        if (!self::$instance) {
            self::$instance = new mysqli(
                getenv("mysql_host"), 
                getenv("mysql_user"), 
                getenv("mysql_password"), 
                'webchatlearn');
            if (!self::$instance) throw new InvalidDbConnectionException;
            //echo "Conexão ao banco de dados realizada com sucesso!";
        }
        return self::$instance;
    }
}