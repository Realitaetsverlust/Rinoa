<?php

class Category extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "categoryName" => array(
            "datatype" => "VARCHAR(100)",
        )
    );

    public $_existsInDatabase;
    private $_id;
    private $_categoryName;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }

    public function loadById($id) {
        $this->_load(array("id" => $id));
    }

    public function loadByName($categoryName) {
        $this->_load(array("categoryName" => $categoryName));
    }

    public function getArticlesFromCategory($count = 10) {
        $articleIds = $this->query("SELECT id FROM aerith_Article WHERE categoryId = {$this->getId()} LIMIT {$count}");

        foreach($articleIds as $articleId) {
            $articleObject = new Article();
            $articleObject->loadById($articleId['id']);
            $articleList[] = $articleObject;
        }

        return $articleList;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return mixed
     */
    public function getCategoryName()
    {
        return $this->_categoryName;
    }

    /**
     * @param mixed $categoryName
     */
    public function setCategoryName($categoryName)
    {
        $this->_categoryName = $categoryName;
    }

}