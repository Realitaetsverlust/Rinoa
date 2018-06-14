<?php

class Users extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "name" => array(
            "datatype" => "VARCHAR(50)"
        ),
        "password" => array(
            "datatype" => "VARCHAR(60)"
        ),
        "email" => array(
            "datatype" => "VARCHAR(255)"
        ),
        "twitter" => array(
            "datatype" => "VARCHAR(20)"
        ),
        "registeredAt" => array(
            "datatype" => "TIMESTAMP",
            "default" => "CURRENT_TIMESTAMP"
        ),
        "rights" => array(
            "datatype" => "INT(11)"
        )
    );

    public $_existsInDatabase;
    private $_id;
    private $_name;
    private $_password;
    private $_email;
    private $_twitter;
    private $_rights;
    private $_registeredAt;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }

    public function loadById($id) {
        $this->_load(array("id" => $id));
    }

    public function loadByName($name) {
        $this->_load(array("name" => $name));
    }

    public function createPasswordHash($password) {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verifyPassword($password) {
        return password_verify($password, $this->getPassword());
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
    public function getName()
    {
        return $this->_name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->_name = $name;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * @return mixed
     */
    public function getTwitter()
    {
        return $this->_twitter;
    }

    /**
     * @param mixed $twitter
     */
    public function setTwitter($twitter)
    {
        $this->_twitter = $twitter;
    }

    /**
     * @return mixed
     */
    public function getRights()
    {
        return $this->_rights;
    }

    /**
     * @param mixed $twitter
     */
    public function setRights($rights)
    {
        $this->_rights = $rights;
    }

    /**
     * @return mixed
     */
    public function getRegisteredAt()
    {
        return $this->_registeredAt;
    }

    /**
     * @param mixed $registeredAt
     */
    public function setRegisteredAt($registeredAt)
    {
        $this->_registeredAt = $registeredAt;
    }
    
}