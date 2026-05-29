<?php
require_once BASE_PATH.'/core/Controller.php';
class DonController extends Controller {
    public function index(): void { $this->formulaire(); }
    public function formulaire(): void { $this->render('don/formulaire', ['pageTitle'=>'Faire un don — PharmaLink']); }
    public function traiter(): void {
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            $pdo = getPDO();
            $pdo->prepare("INSERT INTO dons (nom_donateur,email_donateur,montant,methode_paiement,statut,created_at) VALUES (?,?,?,?,'en_attente',NOW())")
                ->execute([Security::sanitize($_POST['nom_donateur']??'Anonyme'),Security::sanitize($_POST['email_donateur']??''),max(500,(float)$_POST['montant']),Security::sanitize($_POST['methode_paiement']??'mtn_momo')]);
            $id = $pdo->lastInsertId();
            $this->redirect(BASE_URL.'/index.php?page=don&action=confirmation&id='.$id);
        }
        $this->redirect(BASE_URL.'/index.php?page=don');
    }
    public function confirmation(): void {
        $pdo = getPDO();
        $id  = (int)($_GET['id'] ?? 0);
        $don = $id ? $pdo->prepare("SELECT * FROM dons WHERE id=?") : null;
        if ($don) { $don->execute([$id]); $don = $don->fetch(); }
        $this->render('don/confirmation', compact('don') + ['pageTitle'=>'Don confirmé — PharmaLink']);
    }
}
