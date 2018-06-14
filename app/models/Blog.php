<?php

class Blog extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "title" => array(
            "datatype" => "VARCHAR(100)"
        )
    );

    private $_existsInDatabase;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }
}