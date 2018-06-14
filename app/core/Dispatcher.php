<?php

class Dispatcher extends SuperConfig
{
    public function __construct()
    {
        parent::__construct();
    }

    public function dispatch()
    {
        $route = $this->_getCurrentUri();
        $this->_matchCustomRoutes($route);

        if ($this->_doesControllerToRouteExist($route)) {
            if($route->getController() === 'Static') {
                if(file_exists(SuperConfig::getTplDir().$filename = $route->getParams().'.tpl')){
                    return $route;
                } else {
                    $routingExceptionRoute = new Route();
                    $routingExceptionRoute->setController('RoutingException');
                    $routingExceptionRoute->setParams($route->getController());
                    return $routingExceptionRoute;
                }
            } else {
                return $route;
            }
        } else {
            $routingExceptionRoute = new Route();
            $routingExceptionRoute->setController('RoutingException');
            $routingExceptionRoute->setParams($route->getController());
            return $routingExceptionRoute;
        }
    }

    private function _matchCustomRoutes($route)
    {
        $yamlContent = yaml_parse_file(realpath(SuperConfig::getYamlDir() . 'routes.yaml'));

        foreach ($yamlContent as $customRouteName => $customRouteData) {
            if ($route->getController() == $customRouteName) {
                if ($customRouteData['type'] === 'static') {
                    $route->setController('Static');
                    $route->setMethod('loadStaticTemplate');
                    if (!isset($customRouteData['resource'])) {
                        $route->setParams($customRouteName);
                    } else {
                        $route->setParams($customRouteData['resource']);
                    }
                    return true;
                } elseif ($customRouteData['type'] === 'url') {
                    $route->setController('ExternalUrl');
                    $route->setMethod('loadExternalUrl');
                    $route->setParams($customRouteData['resource']);
                    return true;
                } elseif ($customRouteData['type'] === 'controller') {
                    $route->setController($customRouteData['resource']);
                    $route->setMethod($customRouteData['method']);
                    return true;
                }
            }
        }

        return $route;
    }

    private function _getCurrentUri()
    {
        $route = new Route();
        $paramNr = 1;

        $uriPartsRaw = explode('/', substr($_SERVER['REQUEST_URI'], 1));

        if ($uriPartsRaw[0] == '') {
            $route->setController('Main');
            $route->setMethod('render');
        } else {
            $route->setController($uriPartsRaw[0]);
            if (isset($uriPartsRaw[1])) {
                $route->setMethod($uriPartsRaw[1]);
                unset($uriPartsRaw[1]);
            }
            unset($uriPartsRaw[0]);

            if (!empty($uriPartsRaw)) {
                foreach ($uriPartsRaw as $uriPart) {
                    $params['param_' . $paramNr] = $uriPart;
                    $paramNr++;
                }
                $route->setParams($params);
            }
        }

        return $route;
    }

    private function _doesControllerToRouteExist($route)
    {
        if (file_exists(SuperConfig::getControllersDir() . $route->getControllerName() . '.php')) {
            return true;
        }

        return false;
    }
}