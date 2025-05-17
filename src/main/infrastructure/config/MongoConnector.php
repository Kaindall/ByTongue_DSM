<?php
require_once 'src/main/infrastructure/config/Logger.php';
use MongoDB\Driver\Manager;

class MongoConnector {
    private static $instance;

    private function __construct() {}

    public static function getInstance(): Manager {
        if (!self::$instance) {
            $mongo_user = getenv('mongo_user');
            $mongo_pw = getenv('mongo_password');
            $uri = "mongodb+srv://$mongo_user:$mongo_pw@bytongue.xurug.mongodb.net/?retryWrites=true&w=majority&appName=byTongue";
            self::$instance = new Manager($uri);
            if (!self::$instance) throw new InvalidDbConnectionException;
            Logger::info("Conexão ao banco de dados realizada com sucesso!");
        }
        return self::$instance;
    }
}