<?php
require_once BASE_PATH . '/core/Controller.php';

class AdminController extends Controller {

    public function __construct() {
        $this->requireAdmin();
    }

    /* ── Dashboard ─────────────────────────────────────── */
    public function dashboard(): void {
        $pdo = getPDO();

        $stats = [
            'pharmacies'  => (int)$pdo->query("SELECT COUNT(*) FROM pharmacies")->fetchColumn(),
            'medicaments' => (int)$pdo->query("SELECT COUNT(*) FROM medicaments")->fetchColumn(),
            'utilisateurs'=> (int)$pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn(),
            'commandes'   => (int)$pdo->query("SELECT COUNT(*) FROM commandes")->fetchColumn(),
            'articles'    => (int)$pdo->query("SELECT COUNT(*) FROM articles")->fetchColumn(),
            'contacts'    => (int)$pdo->query("SELECT COUNT(*) FROM contacts WHERE lu=0")->fetchColumn(),
            'dons'        => (float)$pdo->query("SELECT COALESCE(SUM(montant),0) FROM dons WHERE statut='confirme'")->fetchColumn(),
            'missions'    => (int)$pdo->query("SELECT COUNT(*) FROM missions")->fetchColumn(),
        ];

        // Recent commandes
        $recentes = $pdo->query("SELECT c.*, u.nom, u.prenom FROM commandes c LEFT JOIN utilisateurs u ON c.utilisateur_id=u.id ORDER BY c.created_at DESC LIMIT 8")->fetchAll();

        // Commandes par statut
        $cmdStatuts = $pdo->query("SELECT statut, COUNT(*) as nb FROM commandes GROUP BY statut")->fetchAll();

        // Stocks faibles
        $stocksFaibles = $pdo->query("SELECT m.nom, m.stock_global FROM medicaments m WHERE m.stock_global < 30 ORDER BY m.stock_global ASC LIMIT 5")->fetchAll();

        $this->render('admin/dashboard', compact('stats','recentes','cmdStatuts','stocksFaibles','pdo') + ['pageTitle' => 'Tableau de bord Admin']);
    }

    /* ── Pharmacies ─────────────────────────────────────── */
    public function pharmacies(): void {
        $pdo = getPDO();
        $search = Security::sanitize($_GET['q'] ?? '');
        $zone_id = (int)($_GET['zone'] ?? 0);

        $sql = "SELECT p.*, z.nom as zone_nom FROM pharmacies p LEFT JOIN zones z ON p.zone_id=z.id WHERE 1=1";
        $params = [];
        if ($search) { $sql .= " AND (p.nom LIKE ? OR p.adresse LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($zone_id) { $sql .= " AND p.zone_id=?"; $params[] = $zone_id; }
        $sql .= " ORDER BY p.nom ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $pharmacies = $stmt->fetchAll();
        $zones = $pdo->query("SELECT * FROM zones ORDER BY nom")->fetchAll();

        $this->render('admin/pharmacies', compact('pharmacies','zones','search','zone_id') + ['pageTitle' => 'Gestion des Pharmacies']);
    }

    public function pharmacie_add(): void {
        $pdo = getPDO();
        $zones = $pdo->query("SELECT * FROM zones ORDER BY nom")->fetchAll();
        $error = ''; $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $data = [
                    Security::sanitize($_POST['nom'] ?? ''),
                    Security::sanitize($_POST['adresse'] ?? ''),
                    Security::sanitize($_POST['telephone'] ?? ''),
                    Security::sanitize($_POST['email'] ?? ''),
                    Security::sanitize($_POST['horaires'] ?? ''),
                    $_POST['statut'] ?? 'ouvert',
                    $_POST['zone_id'] ?? 1,
                    $_POST['latitude'] ?? null,
                    $_POST['longitude'] ?? null,
                ];
                if (!$data[0] || !$data[1]) { $error = 'Nom et adresse obligatoires.'; }
                else {
                    $pdo->prepare("INSERT INTO pharmacies (nom,adresse,telephone,email,horaires,statut,zone_id,latitude,longitude) VALUES (?,?,?,?,?,?,?,?,?)")->execute($data);
                    $this->redirect(BASE_URL . '/index.php?page=admin&action=pharmacies&msg=pharmacie_ajoutee');
                }
            }
        }
        $this->render('admin/pharmacie_form', compact('zones','error','success') + ['pharmacie'=>null,'pageTitle'=>'Ajouter une pharmacie']);
    }

    public function pharmacie_edit(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM pharmacies WHERE id=?");
        $stmt->execute([$id]);
        $pharmacie = $stmt->fetch();
        if (!$pharmacie) { $this->redirect(BASE_URL . '/index.php?page=admin&action=pharmacies'); }

        $zones = $pdo->query("SELECT * FROM zones ORDER BY nom")->fetchAll();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo->prepare("UPDATE pharmacies SET nom=?,adresse=?,telephone=?,email=?,horaires=?,statut=?,zone_id=?,latitude=?,longitude=? WHERE id=?")
                    ->execute([
                        Security::sanitize($_POST['nom']),
                        Security::sanitize($_POST['adresse']),
                        Security::sanitize($_POST['telephone']),
                        Security::sanitize($_POST['email']),
                        Security::sanitize($_POST['horaires']),
                        $_POST['statut'],
                        $_POST['zone_id'],
                        $_POST['latitude'] ?: null,
                        $_POST['longitude'] ?: null,
                        $id
                    ]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=pharmacies&msg=pharmacie_modifiee');
            }
        }
        $this->render('admin/pharmacie_form', compact('pharmacie','zones','error') + ['pageTitle'=>'Modifier la pharmacie']);
    }

    public function pharmacie_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id && Security::checkCsrf()) {
            $pdo->prepare("DELETE FROM pharmacies WHERE id=?")->execute([$id]);
        }
        $this->redirect(BASE_URL . '/index.php?page=admin&action=pharmacies&msg=pharmacie_supprimee');
    }

    /* ── Médicaments ─────────────────────────────────────── */
    public function medicaments(): void {
        $pdo = getPDO();
        $search = Security::sanitize($_GET['q'] ?? '');
        $cat_id = (int)($_GET['cat'] ?? 0);

        $sql = "SELECT m.*, c.nom as cat_nom FROM medicaments m LEFT JOIN categories c ON m.categorie_id=c.id WHERE 1=1";
        $params = [];
        if ($search) { $sql .= " AND (m.nom LIKE ? OR m.dci LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
        if ($cat_id) { $sql .= " AND m.categorie_id=?"; $params[] = $cat_id; }
        $sql .= " ORDER BY m.nom ASC";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $medicaments = $stmt->fetchAll();
        $categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();

        $this->render('admin/medicaments', compact('medicaments','categories','search','cat_id') + ['pageTitle' => 'Gestion des Médicaments']);
    }

    public function medicament_add(): void {
        $pdo = getPDO();
        $categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $nom = Security::sanitize($_POST['nom'] ?? '');
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nom));
                if (!$nom) { $error = 'Nom obligatoire.'; }
                else {
                    $pdo->prepare("INSERT INTO medicaments (categorie_id,nom,dci,dosage,forme,description,prix,stock_global,ordonnance,slug) VALUES (?,?,?,?,?,?,?,?,?,?)")
                        ->execute([$_POST['categorie_id'], $nom, $_POST['dci'], $_POST['dosage'], $_POST['forme'], $_POST['description'], $_POST['prix'], $_POST['stock_global'], isset($_POST['ordonnance'])?1:0, $slug]);
                    $this->redirect(BASE_URL . '/index.php?page=admin&action=medicaments&msg=medicament_ajoute');
                }
            }
        }
        $this->render('admin/medicament_form', compact('categories','error') + ['medicament'=>null,'pageTitle'=>'Ajouter un médicament']);
    }

    public function medicament_edit(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM medicaments WHERE id=?");
        $stmt->execute([$id]);
        $medicament = $stmt->fetch();
        if (!$medicament) { $this->redirect(BASE_URL . '/index.php?page=admin&action=medicaments'); }

        $categories = $pdo->query("SELECT * FROM categories ORDER BY nom")->fetchAll();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo->prepare("UPDATE medicaments SET categorie_id=?,nom=?,dci=?,dosage=?,forme=?,description=?,prix=?,stock_global=?,ordonnance=? WHERE id=?")
                    ->execute([$_POST['categorie_id'], $_POST['nom'], $_POST['dci'], $_POST['dosage'], $_POST['forme'], $_POST['description'], $_POST['prix'], $_POST['stock_global'], isset($_POST['ordonnance'])?1:0, $id]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=medicaments&msg=medicament_modifie');
            }
        }
        $this->render('admin/medicament_form', compact('medicament','categories','error') + ['pageTitle'=>'Modifier le médicament']);
    }

    public function medicament_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM medicaments WHERE id=?")->execute([$id]);
        $this->redirect(BASE_URL . '/index.php?page=admin&action=medicaments&msg=medicament_supprime');
    }

    /* ── Commandes ─────────────────────────────────────── */
    public function commandes(): void {
        $pdo = getPDO();
        $statut = Security::sanitize($_GET['statut'] ?? '');
        $sql = "SELECT c.*, u.nom, u.prenom, u.email as user_email FROM commandes c LEFT JOIN utilisateurs u ON c.utilisateur_id=u.id WHERE 1=1";
        $params = [];
        if ($statut) { $sql .= " AND c.statut=?"; $params[] = $statut; }
        $sql .= " ORDER BY c.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $commandes = $stmt->fetchAll();
        $this->render('admin/commandes', compact('commandes','statut') + ['pageTitle'=>'Gestion des Commandes']);
    }

    public function commande_detail(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT c.*, u.nom, u.prenom, u.email as user_email, u.telephone FROM commandes c LEFT JOIN utilisateurs u ON c.utilisateur_id=u.id WHERE c.id=?");
        $stmt->execute([$id]);
        $commande = $stmt->fetch();
        if (!$commande) { $this->redirect(BASE_URL . '/index.php?page=admin&action=commandes'); }

        $lignes = $pdo->prepare("SELECT cl.*, m.nom as med_nom FROM commande_lignes cl JOIN medicaments m ON cl.medicament_id=m.id WHERE cl.commande_id=?");
        $lignes->execute([$id]);
        $lignes = $lignes->fetchAll();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['statut'])) {
            $pdo->prepare("UPDATE commandes SET statut=? WHERE id=?")->execute([$_POST['statut'], $id]);
            $this->redirect(BASE_URL . '/index.php?page=admin&action=commande_detail&id=' . $id . '&msg=statut_maj');
        }

        $this->render('admin/commande_detail', compact('commande','lignes') + ['pageTitle'=>'Détail commande #'.$id]);
    }

    /* ── Utilisateurs ─────────────────────────────────────── */
    public function utilisateurs(): void {
        $pdo = getPDO();
        $search = Security::sanitize($_GET['q'] ?? '');
        $sql = "SELECT * FROM utilisateurs WHERE 1=1";
        $params = [];
        if ($search) { $sql .= " AND (nom LIKE ? OR prenom LIKE ? OR email LIKE ?)"; $params = array_fill(0,3,"%$search%"); }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $utilisateurs = $stmt->fetchAll();
        $this->render('admin/utilisateurs', compact('utilisateurs','search') + ['pageTitle'=>'Gestion des Utilisateurs']);
    }

    public function utilisateur_toggle(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $id !== (int)$_SESSION['user']['id']) {
            $user = $pdo->prepare("SELECT role FROM utilisateurs WHERE id=?");
            $user->execute([$id]);
            $u = $user->fetch();
            $newRole = ($u['role'] === 'admin') ? 'client' : 'admin';
            $pdo->prepare("UPDATE utilisateurs SET role=? WHERE id=?")->execute([$newRole, $id]);
        }
        $this->redirect(BASE_URL . '/index.php?page=admin&action=utilisateurs');
    }

    public function utilisateur_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id && $id !== (int)$_SESSION['user']['id']) {
            $pdo->prepare("DELETE FROM utilisateurs WHERE id=?")->execute([$id]);
        }
        $this->redirect(BASE_URL . '/index.php?page=admin&action=utilisateurs');
    }

    /* ── Articles/Blog ─────────────────────────────────────── */
    public function articles(): void {
        $pdo = getPDO();
        $articles = $pdo->query("SELECT a.*, u.nom, u.prenom FROM articles a LEFT JOIN utilisateurs u ON a.auteur_id=u.id ORDER BY a.created_at DESC")->fetchAll();
        $this->render('admin/articles', compact('articles') + ['pageTitle'=>'Gestion du Blog']);
    }

    public function article_add(): void {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo = getPDO();
                $titre = Security::sanitize($_POST['titre'] ?? '');
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $titre)) . '-' . time();
                $pdo->prepare("INSERT INTO articles (auteur_id,titre,slug,contenu,extrait,tag,statut) VALUES (?,?,?,?,?,?,?)")
                    ->execute([$_SESSION['user']['id'], $titre, $slug, $_POST['contenu'], $_POST['extrait'], $_POST['tag'], $_POST['statut']]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=articles&msg=article_ajoute');
            }
        }
        $this->render('admin/article_form', compact('error') + ['article'=>null,'pageTitle'=>'Nouvel article']);
    }

    public function article_edit(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM articles WHERE id=?");
        $stmt->execute([$id]);
        $article = $stmt->fetch();
        if (!$article) { $this->redirect(BASE_URL . '/index.php?page=admin&action=articles'); }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo->prepare("UPDATE articles SET titre=?,contenu=?,extrait=?,tag=?,statut=? WHERE id=?")
                    ->execute([$_POST['titre'], $_POST['contenu'], $_POST['extrait'], $_POST['tag'], $_POST['statut'], $id]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=articles&msg=article_modifie');
            }
        }
        $this->render('admin/article_form', compact('article','error') + ['pageTitle'=>'Modifier l\'article']);
    }

    public function article_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM articles WHERE id=?")->execute([$id]);
        $this->redirect(BASE_URL . '/index.php?page=admin&action=articles');
    }

    /* ── Contacts ─────────────────────────────────────── */
    public function contacts(): void {
        $pdo = getPDO();
        $contacts = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC")->fetchAll();
        $pdo->query("UPDATE contacts SET lu=1");
        $this->render('admin/contacts', compact('contacts') + ['pageTitle'=>'Messages de contact']);
    }

    public function contact_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM contacts WHERE id=?")->execute([$id]);
        $this->redirect(BASE_URL . '/index.php?page=admin&action=contacts');
    }

    /* ── Dons ─────────────────────────────────────── */
    public function dons(): void {
        $pdo = getPDO();
        $dons = $pdo->query("SELECT * FROM dons ORDER BY created_at DESC")->fetchAll();
        $total = $pdo->query("SELECT COALESCE(SUM(montant),0) FROM dons WHERE statut='confirme'")->fetchColumn();
        $this->render('admin/dons', compact('dons','total') + ['pageTitle'=>'Gestion des Dons']);
    }

    public function don_statut(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $statut = Security::sanitize($_GET['statut'] ?? '');
        if ($id && in_array($statut, ['confirme','echoue','en_attente'])) {
            $pdo->prepare("UPDATE dons SET statut=? WHERE id=?")->execute([$statut, $id]);
        }
        $this->redirect(BASE_URL . '/index.php?page=admin&action=dons');
    }

    /* ── Missions ─────────────────────────────────────── */
    public function missions(): void {
        $pdo = getPDO();
        $missions = $pdo->query("SELECT * FROM missions ORDER BY date_debut DESC")->fetchAll();
        $this->render('admin/missions', compact('missions') + ['pageTitle'=>'Missions Humanitaires']);
    }

    public function mission_add(): void {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo = getPDO();
                $pdo->prepare("INSERT INTO missions (titre,zone,date_debut,date_fin,bilan,statut) VALUES (?,?,?,?,?,?)")
                    ->execute([$_POST['titre'], $_POST['zone'], $_POST['date_debut'], $_POST['date_fin'], $_POST['bilan'], $_POST['statut']]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=missions&msg=mission_ajoutee');
            }
        }
        $this->render('admin/mission_form', compact('error') + ['mission'=>null,'pageTitle'=>'Nouvelle mission']);
    }

    public function mission_edit(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        $stmt = $pdo->prepare("SELECT * FROM missions WHERE id=?");
        $stmt->execute([$id]);
        $mission = $stmt->fetch();
        if (!$mission) { $this->redirect(BASE_URL . '/index.php?page=admin&action=missions'); }

        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $pdo->prepare("UPDATE missions SET titre=?,zone=?,date_debut=?,date_fin=?,bilan=?,statut=? WHERE id=?")
                    ->execute([$_POST['titre'], $_POST['zone'], $_POST['date_debut'], $_POST['date_fin'], $_POST['bilan'], $_POST['statut'], $id]);
                $this->redirect(BASE_URL . '/index.php?page=admin&action=missions&msg=mission_modifiee');
            }
        }
        $this->render('admin/mission_form', compact('mission','error') + ['pageTitle'=>'Modifier la mission']);
    }

    public function mission_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM missions WHERE id=?")->execute([$id]);
        $this->redirect(BASE_URL . '/index.php?page=admin&action=missions');
    }

    /* ── Stocks ─────────────────────────────────────── */
    public function stocks(): void {
        $pdo = getPDO();
        $stocks = $pdo->query("SELECT s.*, m.nom as med_nom, p.nom as pharm_nom FROM stocks s JOIN medicaments m ON s.medicament_id=m.id JOIN pharmacies p ON s.pharmacie_id=p.id ORDER BY s.quantite ASC")->fetchAll();
        $this->render('admin/stocks', compact('stocks') + ['pageTitle'=>'Gestion des Stocks']);
    }

    /* ── Catégories ─────────────────────────────────────── */
    public function categories(): void {
        $pdo = getPDO();
        $categories = $pdo->query("SELECT c.*, COUNT(m.id) as nb_meds FROM categories c LEFT JOIN medicaments m ON c.id=m.categorie_id GROUP BY c.id ORDER BY c.nom")->fetchAll();
        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::checkCsrf()) { $error = 'Token invalide.'; }
            else {
                $nom = Security::sanitize($_POST['nom'] ?? '');
                $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $nom));
                $icone = Security::sanitize($_POST['icone'] ?? '💊');
                if ($nom) {
                    $pdo->prepare("INSERT INTO categories (nom,slug,icone) VALUES (?,?,?)")->execute([$nom,$slug,$icone]);
                    $this->redirect(BASE_URL . '/index.php?page=admin&action=categories');
                }
            }
        }
        $this->render('admin/categories', compact('categories','error') + ['pageTitle'=>'Catégories']);
    }

    public function categorie_delete(): void {
        $pdo = getPDO();
        $id = (int)($_GET['id'] ?? 0);
        if ($id) $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
        $this->redirect(BASE_URL . '/index.php?page=admin&action=categories');
    }

    /* ── Newsletter ─────────────────────────────────────── */
    public function newsletter(): void {
        $pdo = getPDO();
        $abonnes = $pdo->query("SELECT * FROM newsletter ORDER BY created_at DESC")->fetchAll();
        $this->render('admin/newsletter', compact('abonnes') + ['pageTitle'=>'Newsletter']);
    }
    /* ── Profil ─────────────────────────────────────── */
    public function profil(): void {
        $this->render('admin/profil', ['pageTitle' => 'Mon Profil']);
    }

    /* ── Statistiques ───────────────────────────────── */
    public function statistiques(): void {
        $this->render('admin/statistiques', ['pageTitle' => 'Statistiques']);
    }

    /* ── Carte ──────────────────────────────────────── */
    public function carte(): void {
        $this->render('admin/carte', ['pageTitle' => 'Carte des Pharmacies']);
    }
}