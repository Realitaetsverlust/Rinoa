<?php

class ArticleController extends Controller {
    public function readArticle($params) {
        $this->_setTemplateName();
        $articleObject = new Article();
        $articleObject->loadById($params['param_1']);
        $imageInfo = $articleObject->getTitleImageInformation();
        $this->assign("article", $articleObject);
        $this->assign("imageInfo", $imageInfo);
        $this->display();
    }
}