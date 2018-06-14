<?php

class Article extends ModelBase {
    protected $_expectedFieldNames = array(
        "id" => array(
            "datatype" => "INT(11)",
            "attribute" => "UNSIGNED",
            "index" => "PRIMARY KEY",
            "auto_increment" => "true"
        ),
        "headline" => array(
            "datatype" => "VARCHAR(255)"
        ),
        "text" => array(
            "datatype" => "TEXT"
        ),
        "authorId" => array(
            "datatype" => "INT(11)"
        ),
        "categoryId" => array(
            "datatype" => "INT(11)"
        ),
        "mediaId" => array(
            "datatype" => "INT(11)"
        ),
        "createdAt" => array(
            "datatype" => "TIMESTAMP",
            "default" => "CURRENT_TIMESTAMP"
        )
    );

    public $_existsInDatabase;
    private $_id;
    private $_headline;
    private $_text;
    private $_authorId;
    private $_mediaId;
    private $_createdAt;
    private $_categoryId;

    public function __construct() {
        $this->_tableName = SuperConfig::getYamlContent("Database")->dbprefix.__CLASS__;
        parent::__construct();
        $this->_existsInDatabase = false;
    }

    public function loadById($id) {
        $this->_load(array("id" => $id));
    }

    public function searchInTitleForString($searchParam) {
        $searchParam = "%{$searchParam}%";
        $stmt = $this->_db->prepare('SELECT id FROM aerith_Article WHERE headline LIKE :search');
        $stmt->bindParam("search", $searchParam);
        $stmt->execute();
        foreach($stmt->fetchAll() as $id) {
            $articleObject = new Article();
            $articleObject->loadById($id['id']);
            $foundArticles[] = $articleObject;
        }
        return $foundArticles;
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
    public function getHeadline()
    {
        return $this->_headline;
    }

    /**
     * @param mixed $headline
     */
    public function setHeadline($headline)
    {
        $this->_headline = $headline;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->_text;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->_text = $text;
    }

    /**
     * @return mixed
     */
    public function getAuthorId()
    {
        return $this->_authorId;
    }

    /**
     * @param mixed $authorId
     */
    public function setAuthorId($authorId)
    {
        $this->_authorId = $authorId;
    }

    /**
     * @return mixed
     */
    public function getMediaId()
    {
        return $this->_mediaId;
    }

    /**
     * @param mixed $titleImageId
     */
    public function setMediaId($mediaId)
    {
        $this->_mediaId = $mediaId;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->_createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->_createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCategoryId()
    {
        return $this->_categoryId;
    }

    /**
     * @param mixed $categoryId
     */
    public function setCategoryId($categoryId)
    {
        $this->_categoryId = $categoryId;
    }

    public function getAuthorName() {
        $author = new Users();
        $author->loadById($this->getAuthorId());
        return $author->getName();
    }

    public function getCategoryName() {
        $category = new Category();
        $category->loadById($this->getCategoryId());
        return $category->getCategoryName();
    }

    public function getTitleImageInformation() {
        $media = new Media();
        $media->loadById($this->getMediaId());
        $imageInfo['subline'] = $media->getImageSubline();
        $imageInfo['alt'] = $media->getImageName();
        $imageInfo['path'] = "/public/uploads/media/{$this->getMediaId()}.jpg";

        return $imageInfo;
    }

    public function getTextSnippet($length = 30) {
        return implode(" ", array_slice(explode(" ", $this->getText()), 0, $length))." ...";
    }

    public function getUrlForArticle() {
        return Route::getUrlForController("Article", "readArticle", array($this->getId()));
    }

}