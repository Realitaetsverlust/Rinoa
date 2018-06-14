<?php

class StaticController extends Controller {
    public function loadStaticTemplate($params) {
        parent::render();
        $this->_setTemplateName($params.".tpl");
        $this->display();
    }
}