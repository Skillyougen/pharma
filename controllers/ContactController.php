<?php
require_once BASE_PATH.'/core/Controller.php';
class ContactController extends Controller {
    public function index(): void {
        $ok=''; $err='';
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $pdo=getPDO();
            $pdo->prepare("INSERT INTO contacts (nom,email,telephone,sujet,message,created_at) VALUES (?,?,?,?,?,NOW())")
                ->execute([Security::sanitize($_POST['nom']??''),Security::sanitize($_POST['email']??''),Security::sanitize($_POST['telephone']??''),Security::sanitize($_POST['sujet']??''),Security::sanitize($_POST['message']??'')]);
            $ok='Message envoyé avec succès !';
        }
        $this->render('contact/index', compact('ok','err') + ['pageTitle'=>'Contact — PharmaLink']);
    }
}
