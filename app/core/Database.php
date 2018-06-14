<?php

class Database extends SuperConfig {

    private $_pdo;

    public function __clone() {
        return false;
    }

    public function __wakeup() {
        return false;
    }

    public function __construct() {
        parent::__construct();

        $charset = "utf8";
        $dsn = "mysql:host=". SuperConfig::getYamlContent("Database")->dbhost.";dbname=".SuperConfig::getYamlContent("Database")->dbname.";charset=$charset";

        $opt = array(
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        );

        $this->_pdo = new PDO($dsn, SuperConfig::getYamlContent("Database")->dbuser, SuperConfig::getYamlContent("Database")->dbpassword, $opt);
    }

    public function getDb() {
        return $this->_pdo;
    }

    public function query() {

    }
}