<?php

class Route extends SuperConfig {
    private $_controller;
    private $_method;
    private $_params;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->_controller;
    }

    /**
     * @param mixed $controller
     */
    public function setController($controller)
    {
        $this->_controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        if(!$this->_method) {
            return 'render';
        }
        return $this->_method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->_method = $method;
    }

    /**
     * @param $paramNr int the param nr
     * @return mixed
     */
    public function getParams($paramNr = null)
    {
        if(!is_null($paramNr)) {
            return $this->_params[$paramNr];
        }
        return $this->_params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }

    public function getControllerName() {
        return $controllerName = $this->getController().'Controller';
    }

    public function getControllerIncludePath() {
        return SuperConfig::getControllersDir() . $this->getControllerName().'.php';
    }

    public static function getUrlForController(string $controller, string $method = null, array $params = array()):string {
        return 'http://'.SuperConfig::getHttpHost() .'/' . $controller . ($method === null ? '' : '/'.$method) . '/' . implode('/', $params);
    }

    public static function rerouteToController(string $controller, string $method = null, array $params = array()) {
        $url = self::getUrlForController($controller, $method, $params);
        header("Location: {$url}");
        exit();
    }

    public static function getUrlForStaticRoute($routeName) {
        return 'http://'.SuperConfig::getHttpHost() .'/' . $routeName;
    }

}