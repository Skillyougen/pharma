<?php
require_once BASE_PATH.'/core/Controller.php';
class CommandeController extends Controller {
    public function index(): void { $this->panier(); }
    public function panier(): void {
        $panier = $_SESSION['panier'] ?? [];
        $this->render('commande/panier', compact('panier') + ['pageTitle'=>'Mon Panier — PharmaLink']);
    }
    public function ajouter(): void {
        $id  = (int)($_POST['medicament_id'] ?? 0);
        $qte = max(1,(int)($_POST['quantite'] ?? 1));
        if ($id) {
            $pdo = getPDO();
            $m   = $pdo->prepare("SELECT id,nom,prix FROM medicaments WHERE id=?");
            $m->execute([$id]); $med = $m->fetch();
            if ($med) {
                if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
                if (isset($_SESSION['panier'][$id])) $_SESSION['panier'][$id]['quantite'] += $qte;
                else $_SESSION['panier'][$id] = ['id'=>$id,'nom'=>$med['nom'],'prix'=>$med['prix'],'quantite'=>$qte];
                $_SESSION['flash'] = ['type'=>'success','msg'=>'✅ '.Security::h($med['nom']).' ajouté au panier.'];
            }
        }
        $ref = $_SERVER['HTTP_REFERER'] ?? BASE_URL.'/index.php?page=commande&action=panier';
        $this->redirect($ref);
    }
    public function retirer(): void {
        $id = (int)($_GET['id'] ?? 0);
        if ($id && isset($_SESSION['panier'][$id])) unset($_SESSION['panier'][$id]);
        $this->redirect(BASE_URL.'/index.php?page=commande&action=panier');
    }
    public function checkout(): void {
        if (!$this->isLoggedIn()) {
            $_SESSION['flash'] = ['type'=>'info','msg'=>'Connectez-vous pour finaliser votre commande.'];
            $this->redirect(BASE_URL.'/index.php?page=auth&action=login');
        }
        $panier = $_SESSION['panier'] ?? [];
        if (empty($panier)) $this->redirect(BASE_URL.'/index.php?page=commande&action=panier');
        $total  = array_sum(array_map(fn($i)=>$i['prix']*$i['quantite'],$panier));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pdo = getPDO();
            $pdo->prepare("INSERT INTO commandes (utilisateur_id,total,mode_livraison,adresse_livraison,note,statut,created_at) VALUES (?,?,?,?,?,'en_attente',NOW())")
                ->execute([$_SESSION['user']['id'],$total,$_POST['mode_livraison']??'retrait',$_POST['adresse_livraison']??'',$_POST['note']??'']);
            $cmd_id = $pdo->lastInsertId();
            foreach ($panier as $item) {
                $pdo->prepare("INSERT INTO commande_lignes (commande_id,medicament_id,quantite,prix_unitaire) VALUES (?,?,?,?)")
                    ->execute([$cmd_id,$item['id'],$item['quantite'],$item['prix']]);
            }
            $_SESSION['panier'] = [];
            $_SESSION['flash']  = ['type'=>'success','msg'=>'✅ Commande passée avec succès !'];
            $this->redirect(BASE_URL.'/index.php?page=commande&action=confirmation&id='.$cmd_id);
        }
        $this->render('commande/checkout', compact('panier','total') + ['pageTitle'=>'Checkout — PharmaLink']);
    }
    public function confirmation(): void {
        $id = (int)($_GET['id'] ?? 0);
        $this->render('commande/confirmation', compact('id') + ['pageTitle'=>'Commande confirmée']);
    }
}
