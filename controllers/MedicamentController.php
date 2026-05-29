<?php
require_once BASE_PATH.'/core/Controller.php';
class MedicamentController extends Controller {
    public function index(): void { $this->catalogue(); }
    public function catalogue(): void {
        $pdo = getPDO();
        $q      = Security::sanitize($_GET['q']   ?? '');
        $cat_id = (int)($_GET['cat'] ?? 0);
        $sql    = "SELECT m.*, c.nom as cat_nom FROM medicaments m LEFT JOIN categories c ON m.categorie_id=c.id WHERE 1=1";
        $params = [];
        if ($q)      { $sql .= " AND (m.nom LIKE ? OR m.dci LIKE ? OR m.description LIKE ?)"; $params[]="%$q%";$params[]="%$q%";$params[]="%$q%"; }
        if ($cat_id) { $sql .= " AND m.categorie_id=?"; $params[]=$cat_id; }
        $sql .= " ORDER BY m.nom ASC";
        $stmt = $pdo->prepare($sql); $stmt->execute($params);
        $medicaments = $stmt->fetchAll();
        $categories  = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
        $this->render('medicament/catalogue', compact('medicaments','categories','q','cat_id') + ['pageTitle'=>'Médicaments — PharmaLink']);
    }
    public function detail(): void {
        $pdo = getPDO();
        $id  = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT m.*, c.nom as cat_nom FROM medicaments m LEFT JOIN categories c ON m.categorie_id=c.id WHERE m.id=?");
        $stmt->execute([$id]); $medicament = $stmt->fetch();
        if (!$medicament) { $this->redirect(BASE_URL.'/index.php?page=medicament'); }
        $stocks = $pdo->prepare("SELECT s.*, p.nom as pharm_nom, p.adresse, p.statut as pharm_statut, p.telephone FROM stocks s JOIN pharmacies p ON s.pharmacie_id=p.id WHERE s.medicament_id=? ORDER BY s.quantite DESC");
        $stocks->execute([$id]); $stocks = $stocks->fetchAll();
        $this->render('medicament/detail', compact('medicament','stocks') + ['pageTitle'=>Security::h($medicament['nom']).' — PharmaLink']);
    }
}
