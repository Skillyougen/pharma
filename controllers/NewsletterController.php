<?php
require_once BASE_PATH.'/core/Controller.php';
class NewsletterController extends Controller {
    public function index(): void {
        $email=Security::sanitize($_POST['email']??'');
        if(filter_var($email,FILTER_VALIDATE_EMAIL)){
            $pdo=getPDO(); $pdo->prepare("INSERT IGNORE INTO newsletter (email,actif,created_at) VALUES (?,1,NOW())")->execute([$email]);
            $_SESSION['flash']=['type'=>'success','msg'=>'✅ Vous êtes abonné à la newsletter !'];
        }
        $this->redirect(BASE_URL.'/index.php');
    }
}
