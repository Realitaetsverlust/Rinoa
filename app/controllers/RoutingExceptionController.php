<?php

class RoutingExceptionController extends Controller {
    protected $_requestedController;

    public function __construct() {
        parent::__construct();
    }

    public function render() {
        $this->_setTemplateName("404.tpl");
        $this->_tpl->display($this->_templateName);
        Logger::writeExceptionLog(2, "Template '' not found, rerouted to 404");
    }

    public function setRequestedController($requestedController) {
        $this->_requestedController = $requestedController;
    }
}