<?php
require_once "core/SuperConfig.php";
require_once "core/Autoloader.php";
require_once "userFunctions.php";

ini_set("display_errors", 1);
error_reporting(E_ERROR);

Autoloader::register();

$kint = new Kint();
$dispatcher = new Dispatcher();
$smarty = new Smarty();

$route = $dispatcher->dispatch();

session_start();

require_once $route->getControllerIncludePath();

$controllerName = $route->getControllerName();
$controller = new $controllerName();

$methodName = $route->getMethod();
$controller->$methodName($route->getParams());


