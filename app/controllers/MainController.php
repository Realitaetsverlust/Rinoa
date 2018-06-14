<?php

class MainController extends Controller {
    public function render() {
        $latestArticles = $this->_fetchLatestArticles();
        foreach($latestArticles as $latestArticle) {
            $articleObject = new Article();
            $articleObject->loadById($latestArticle['id']);
            $articleList[] = $articleObject;
        }

        $this->assign("articleList", $articleList);

        return parent::render();
    }

    private function _fetchLatestArticles($articleCount = 6)  {
        $sql = "SELECT id FROM aerith_Article ORDER BY createdAt DESC LIMIT {$articleCount}";

        $db = (new Database())->getDb();

        return $db->query($sql)->fetchAll();
    }
}