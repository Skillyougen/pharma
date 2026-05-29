<?php
require_once BASE_PATH.'/core/Controller.php';

class HomeController extends Controller {
    public function index(): void {
        $pdo = getPDO();
        $pharmacies  = $pdo->query("SELECT p.*, z.nom as zone_nom FROM pharmacies p LEFT JOIN zones z ON p.zone_id=z.id ORDER BY p.statut='urgence' DESC, p.statut='ouvert' DESC, p.nom ASC LIMIT 6")->fetchAll();
        $medicaments = $pdo->query("SELECT m.*, c.nom as cat_nom FROM medicaments m LEFT JOIN categories c ON m.categorie_id=c.id ORDER BY m.created_at DESC LIMIT 6")->fetchAll();
        $articles    = $pdo->query("SELECT * FROM articles WHERE statut='publie' ORDER BY created_at DESC LIMIT 3")->fetchAll();
        $categories  = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
        $stats = [
            'pharmacies'  => (int)$pdo->query("SELECT COUNT(*) FROM pharmacies")->fetchColumn(),
            'medicaments' => (int)$pdo->query("SELECT COUNT(*) FROM medicaments")->fetchColumn(),
            'ouvertes'    => (int)$pdo->query("SELECT COUNT(*) FROM pharmacies WHERE statut='ouvert'")->fetchColumn(),
            'urgence'     => (int)$pdo->query("SELECT COUNT(*) FROM pharmacies WHERE statut='urgence'")->fetchColumn(),
            'zones'       => (int)$pdo->query("SELECT COUNT(*) FROM zones")->fetchColumn(),
        ];
        $this->render('home/index', compact('pharmacies','medicaments','articles','categories','stats') + ['pageTitle' => 'PharmaLink — Annuaire Pharmaceutique de Douala']);
    }
}
