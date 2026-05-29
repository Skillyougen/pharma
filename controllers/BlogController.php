<?php
require_once BASE_PATH.'/core/Controller.php';
class BlogController extends Controller {
    public function index(): void {
        $pdo=$pdo=getPDO(); $articles=$pdo->query("SELECT * FROM articles WHERE statut='publie' ORDER BY created_at DESC")->fetchAll();
        $this->render('blog/liste',compact('articles')+['pageTitle'=>'Blog Santé — PharmaLink']);
    }
    public function detail(): void {
        $pdo=getPDO(); $id=(int)($_GET['id']??0);
        $stmt=$pdo->prepare("SELECT * FROM articles WHERE id=? AND statut='publie'"); $stmt->execute([$id]); $article=$stmt->fetch();
        if(!$article){$this->redirect(BASE_URL.'/index.php?page=blog');}
        $this->render('blog/detail',compact('article')+['pageTitle'=>Security::h($article['titre']).' — PharmaLink']);
    }
}
