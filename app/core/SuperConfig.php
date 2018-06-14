<?php

class SuperConfig {
    private static $_yamlContents;
    private static $_httpHost;
    private static $_baseDir;
    private static $_controllersDir;
    private static $_libDir;
    private static $_modulesDir;
    private static $_modelsDir;
    private static $_logDir;
    private static $_yamlDir;
    private static $_coreDir;
    private static $_tplDir;
    private static $_uploadDir;

    public function __construct() {
        self::_parseSettingsYaml();
        self::_setupDirs();
    }

    protected static function _setupDirs() {
        self::$_httpHost = $_SERVER['HTTP_HOST'];
        self::$_baseDir = self::getYamlContent("SuperConfig")->docroot;
        self::$_controllersDir = self::$_baseDir.'/app/controllers/';
        self::$_libDir = self::$_baseDir.'/app/lib/';
        self::$_modulesDir = self::$_baseDir.'/app/modules/';
        self::$_modelsDir = self::$_baseDir.'/app/models/';
        self::$_logDir = self::$_baseDir.'/share/logs/';
        self::$_yamlDir = self::$_baseDir.'/share/';
        self::$_coreDir = self::$_baseDir.'/app/core/';
        self::$_tplDir = self::$_baseDir.'/public/views/';
        self::$_uploadDir = self::$_baseDir.'/public/uploads/';
    }

    protected static function _parseSettingsYaml():bool {
        self::$_yamlContents = json_decode(json_encode(yaml_parse_file(realpath('./../share/settings.yaml'))));
        if(empty(self::$_yamlContents)) {
            return false;
        } else {
            return true;
        }
    }

    public static function getYamlContent(string $part = null) {
        if(!isset($part)) {
            return self::$_yamlContents;
        } else {
            return self::$_yamlContents->$part;
        }
    }

    /**
     * @return mixed
     */
    public static function getHttpHost()
    {
        return self::$_httpHost;
    }

    /**
     * @return mixed
     */
    public static function getBaseDir()
    {
        return self::$_baseDir;
    }

    /**
     * @return mixed
     */
    public static function getControllersDir()
    {
        return self::$_controllersDir;
    }

    /**
     * @return mixed
     */
    public static function getLibDir()
    {
        return self::$_libDir;
    }

    /**
     * @return mixed
     */
    public static function getModulesDir()
    {
        return self::$_modulesDir;
    }

    /**
     * @return mixed
     */
    public static function getModelsDir()
    {
        return self::$_modelsDir;
    }

    /**
     * @return mixed
     */
    public static function getLogDir()
    {
        return self::$_logDir;
    }

    /**
     * @return mixed
     */
    public static function getYamlDir()
    {
        return self::$_yamlDir;
    }

    /**
     * @return mixed
     */
    public static function getCoreDir()
    {
        return self::$_coreDir;
    }

    /**
     * @return mixed
     */
    public static function getTplDir()
    {
        return self::$_tplDir;
    }
    /**
     * @return mixed
     */
    public static function getUploadDir()
    {
        return self::$_uploadDir;
    }


}