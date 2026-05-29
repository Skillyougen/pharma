<?php
require_once BASE_PATH.'/core/Controller.php';

class PharmacieController extends Controller {
    public function index(): void {
        $pdo   = getPDO();
        $q       = Security::sanitize($_GET['q']    ?? '');
        $zone_id = (int)($_GET['zone'] ?? 0);
        $statut  = Security::sanitize($_GET['statut'] ?? '');
        $sql    = "SELECT p.*, z.nom as zone_nom FROM pharmacies p LEFT JOIN zones z ON p.zone_id=z.id WHERE 1=1";
        $params = [];
        if ($q)      { $sql .= " AND (p.nom LIKE ? OR p.adresse LIKE ?)"; $params[] = "%$q%"; $params[] = "%$q%"; }
        if ($zone_id){ $sql .= " AND p.zone_id=?"; $params[] = $zone_id; }
        if ($statut) { $sql .= " AND p.statut=?"; $params[] = $statut; }
        $sql .= " ORDER BY p.statut='urgence' DESC, p.statut='ouvert' DESC, p.nom ASC";
        $stmt = $pdo->prepare($sql); $stmt->execute($params);
        $pharmacies = $stmt->fetchAll();
        $zones = $pdo->query("SELECT * FROM zones ORDER BY nom")->fetchAll();
        $this->render('pharmacie/liste', compact('pharmacies','zones','q','zone_id','statut') + ['pageTitle'=>'Pharmacies — PharmaLink']);
    }

    public function detail(): void {
        $pdo = getPDO();
        $id  = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT p.*, z.nom as zone_nom FROM pharmacies p LEFT JOIN zones z ON p.zone_id=z.id WHERE p.id=?");
        $stmt->execute([$id]);
        $pharmacie = $stmt->fetch();
        if (!$pharmacie) { $this->redirect(BASE_URL.'/index.php?page=pharmacie'); }
        $stocks = $pdo->prepare("SELECT s.*, m.nom as med_nom, m.prix, m.forme, m.dci, m.ordonnance FROM stocks s JOIN medicaments m ON s.medicament_id=m.id WHERE s.pharmacie_id=? ORDER BY m.nom");
        $stocks->execute([$id]);
        $stocks = $stocks->fetchAll();
        $this->render('pharmacie/detail', compact('pharmacie','stocks') + ['pageTitle'=>Security::h($pharmacie['nom']).' — PharmaLink']);
    }
}
