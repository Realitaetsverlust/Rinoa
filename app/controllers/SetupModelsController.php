<?php

class SetupModelsController extends Controller {
    private $_allModelNames;

    public function __construct() {
        $this->_allModelNames = array_diff(scandir(SuperConfig::getModelsDir()), array('..', '.'));
        array_walk($this->_allModelNames,
        function(&$value) {
            $value = preg_replace("/\.php$/", "", $value);
        });
    }

    public function setupModels() {
        foreach($this->_allModelNames as $modelName) {
            $model = new $modelName();
            if(!is_null($model->getExpectedFieldNames())) {
                $model->createTableForModel();
            }
        }
    }
}