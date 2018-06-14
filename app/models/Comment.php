<?php

class Comment extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "commentText" => array(
            "datatype" => "TEXT"
        ),
        "articleId" => array(
            "datatype" => "TEXT"
        ),
        "userId" => array(
            "datatype" => "INT(11)"
        ),
        "createdAt" => array(
            "datatype" => "TIMESTAMP",
            "default" => "CURRENT_TIMESTAMP"
        )
    );

    private $_existsInDatabase;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }
}