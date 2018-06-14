<?php

class Autoloader extends SuperConfig
{
    public function __construct() {
        parent::__construct();
        spl_autoload_register(array($this, 'loadClass'));
    }

    public static function register() {
        new Autoloader();
    }

    public function loadClass($className) {
        //try loading a core class

        if (file_exists(SuperConfig::getCoreDir() . ucfirst($className) . ".php")) {
            require_once(SuperConfig::getCoreDir() . ucfirst($className) . ".php");
            return true;
        }

        //first, try to load a library
        if (file_exists(SuperConfig::getLibDir()."$className/" . ucfirst($className) . ".class.php")) {
            require_once(SuperConfig::getLibDir()."$className/" . ucfirst($className) . ".class.php");
            return true;
        }

        //second, try to load a controller
        if(file_exists(SuperConfig::getControllersDir(). ucfirst($className) . ".php")) {
            require_once(SuperConfig::getControllersDir(). ucfirst($className) . ".php");
            return true;
        }

        //third, try to load a model
        if(file_exists(SuperConfig::getModelsDir(). ucfirst($className) . ".php")) {
            require_once(SuperConfig::getModelsDir(). ucfirst($className) . ".php");
            return true;
        }

        //if all else fails, try to load a module
        if(file_exists(SuperConfig::getModulesDir(). ucfirst($className) . ".php")) {
            require_once(SuperConfig::getModulesDir(). ucfirst($className) . ".php");
            return true;
        }

        return false;
    }
}
