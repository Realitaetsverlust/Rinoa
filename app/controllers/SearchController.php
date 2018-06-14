<?php

class SearchController extends Controller {
    public function searchForCategory($params) {
        $category = new Category();
        $category->loadByName($categoryName = array_pop(array_reverse($params)));
        $foundArticles = $category->getArticlesFromCategory();
        $this->assign("categoryName", $categoryName);
        $this->assign("articleCount", count($foundArticles));
        $this->assign("foundArticles", $foundArticles);
        $this->assign("searchedFor", "die Suche nach");
        $this->assign("searchString", $categoryName);
        parent::render();
    }
    public function searchByString() {
        $this->_setTemplateName();
        $searchParam = $_POST['search'];
        $articleObject = new Article();
        $foundArticles = $articleObject->searchInTitleForString($searchParam);
        $this->assign("foundArticles", $foundArticles);
        $this->assign("articleCount", count($foundArticles));
        $this->assign("searchedFor", "die Suche nach");
        $this->assign("searchString", $searchParam);
        $this->display();
    }
}