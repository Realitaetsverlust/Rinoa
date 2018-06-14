<?php

class Controller extends SuperConfig {

    protected $_templateName;
    protected $_tpl;
    protected $_additionalParams;

    public function __construct() {
        $this->_tpl = new Smarty();
        $this->_tpl->setTemplateDir(SuperConfig::getTplDir());
        $this->_tpl->setCacheDir(SuperConfig::getTplDir().'cache');
        $this->_tpl->setCompileDir(SuperConfig::getTplDir().'template_c');
        $this->_tpl->force_compile = true; //For Dev-Purpose only

        $this->_setupNavigation();

        parent::__construct();
    }

    public function render() {
        $this->_setTemplateName();
        $this->assign('baselink', SuperConfig::getHttpHost());

        if(!($this->_templateName === 'Static.tpl')) {
            $this->display($this->_templateName);
        }
    }

    public function display() {
        $this->_tpl->display($this->_templateName);
    }

    public function assign($varName, $varValue, $nocache = false) {
        $this->_tpl->assign($varName, $varValue, $nocache);
    }

    protected function _setTemplateName($templateName = null) {
        if($templateName === null) {
            $this->_templateName = $this->_getClassName().'.tpl';
        } else {
            $this->_templateName = $templateName;
        }
}

    protected function _getClassName() {
        return substr(get_class($this), 0, -10);
    }
}