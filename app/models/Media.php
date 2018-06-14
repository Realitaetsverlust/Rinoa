<?php

class Media extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "imageName" => array(
            "datatype" => "VARCHAR(255)"
        ),
        "uploaded" => array(
            "datatype" => "TIMESTAMP",
            "default" => "CURRENT_TIMESTAMP"
        ),
        "imageSubline" => array(
            "datatype" => "TEXT"
        )
    );

    public $_existsInDatabase;
    private $_id;
    private $_imageName;
    private $_uploaded;
    private $_imageSubline;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }

    public function loadById($id) {
        $this->_load(array("id" => $id));
    }

    public function saveMedia() {
        parent::save();

        $lastInsertId = $this->query("SELECT id FROM {$this->_tableName} ORDER BY id DESC", 4);
        $fileEnding = explode(".", $_FILES['media']['name'])[1];

        move_uploaded_file($_FILES['media']['tmp_name'], SuperConfig::getUploadDir()."media/{$lastInsertId['id']}.".$fileEnding);
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
    public function getImageName()
    {
        return $this->_imageName;
    }

    /**
     * @param mixed $imageName
     */
    public function setImageName($imageName)
    {
        $this->_imageName = $imageName;
    }

    /**
     * @return mixed
     */
    public function getUploaded()
    {
        return $this->_uploaded;
    }

    /**
     * @param mixed $uploaded
     */
    public function setUploaded($uploaded)
    {
        $this->_uploaded = $uploaded;
    }

    /**
     * @return mixed
     */
    public function getImageSubline()
    {
        return $this->_imageSubline;
    }

    /**
     * @param mixed $imageSubline
     */
    public function setImageSubline($imageSubline)
    {
        $this->_imageSubline = $imageSubline;
    }
    
    
}