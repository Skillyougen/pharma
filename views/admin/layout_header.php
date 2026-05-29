<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'Admin') ?> — PharmaLink</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap">
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin.css">
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/pharmalink-logo.png">
</head>
<body>

<?php
$act = $_GET['action'] ?? 'dashboard';
$usr = $_SESSION['user'];
$ini = mb_strtoupper(mb_substr($usr['prenom'],0,1).mb_substr($usr['nom'],0,1));
$pdo = getPDO();
$nbMsg = (int)$pdo->query("SELECT COUNT(*) FROM contacts WHERE lu=0")->fetchColumn();
?>

<!-- SIDEBAR -->
<aside class="a-sidebar" id="sidebar">
  <div class="a-brand">
    <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="PharmaLink">
    <div class="a-brand-text">
      <h2>PharmaLink</h2>
      <span>Administration</span>
    </div>
  </div>

  <nav class="a-nav">
    <div class="a-nav-section">
      <a href="<?= BASE_URL ?>/index.php?page=admin" class="<?= !isset($_GET['action'])||$act==='dashboard'?'active':'' ?>">
        <span class="nav-ico">📊</span> Tableau de bord
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=statistiques" class="<?= $act==='statistiques'?'active':'' ?>">
        <span class="nav-ico">📈</span> Statistiques
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=carte" class="<?= $act==='carte'?'active':'' ?>">
        <span class="nav-ico">🗺️</span> Carte des pharmacies
      </a>
    </div>

    <div class="a-nav-section">
      <span class="a-nav-label">Annuaire</span>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacies" class="<?= in_array($act,['pharmacies','pharmacie_add','pharmacie_edit'])?'active':'' ?>">
        <span class="nav-ico">🏥</span> Pharmacies
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicaments" class="<?= in_array($act,['medicaments','medicament_add','medicament_edit'])?'active':'' ?>">
        <span class="nav-ico">💊</span> Médicaments
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=categories" class="<?= $act==='categories'?'active':'' ?>">
        <span class="nav-ico">🗂️</span> Catégories
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=stocks" class="<?= $act==='stocks'?'active':'' ?>">
        <span class="nav-ico">📦</span> Stocks
      </a>
    </div>

    <div class="a-nav-section">
      <span class="a-nav-label">Commerce</span>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=commandes" class="<?= in_array($act,['commandes','commande_detail'])?'active':'' ?>">
        <span class="nav-ico">🛒</span> Commandes
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=dons" class="<?= $act==='dons'?'active':'' ?>">
        <span class="nav-ico">❤️</span> Dons
      </a>
    </div>

    <div class="a-nav-section">
      <span class="a-nav-label">Contenu</span>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=articles" class="<?= in_array($act,['articles','article_add','article_edit'])?'active':'' ?>">
        <span class="nav-ico">📝</span> Articles / Blog
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=missions" class="<?= in_array($act,['missions','mission_add','mission_edit'])?'active':'' ?>">
        <span class="nav-ico">🌍</span> Missions
      </a>
    </div>

    <div class="a-nav-section">
      <span class="a-nav-label">Utilisateurs</span>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=utilisateurs" class="<?= $act==='utilisateurs'?'active':'' ?>">
        <span class="nav-ico">👥</span> Comptes
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=newsletter" class="<?= $act==='newsletter'?'active':'' ?>">
        <span class="nav-ico">📧</span> Newsletter
      </a>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=contacts" class="<?= $act==='contacts'?'active':'' ?>">
        <span class="nav-ico">💬</span> Messages
        <?php if($nbMsg>0): ?><span class="a-badge"><?= $nbMsg ?></span><?php endif; ?>
      </a>
    </div>
  </nav>

  <div class="a-footer">
    <div class="a-user">
      <div class="a-avatar"><?= $ini ?></div>
      <div class="a-user-info">
        <p><?= Security::h($usr['prenom'].' '.$usr['nom']) ?></p>
        <span>Administrateur</span>
      </div>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=profil" style="display:flex;align-items:center;gap:8px;padding:7px 12px;background:rgba(255,255,255,.08);color:rgba(255,255,255,.75);border-radius:var(--radius-lg);text-decoration:none;font-size:12px;margin-bottom:6px;transition:background .15s">
      <span>⚙️</span> Mon profil
    </a>
    <a href="<?= BASE_URL ?>/index.php?page=auth&action=logout" class="a-btn-logout">
      <span>🚪</span> Déconnexion
    </a>
  </div>
</aside>

<!-- MAIN -->
<main class="a-main">
  <header class="a-topbar">
    <div style="display:flex;align-items:center;gap:12px">
      <button onclick="toggleAdminNav()" style="background:none;border:none;font-size:20px;cursor:pointer;color:var(--primary);display:none" id="a-burger">☰</button>
      <nav class="a-breadcrumb">
        <a href="<?= BASE_URL ?>/index.php?page=admin">Admin</a>
        <span style="color:var(--border);margin:0 4px">›</span>
        <span><?= htmlspecialchars($pageTitle ?? '') ?></span>
      </nav>
    </div>
    <div class="a-topbar-right">
      <span style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y') ?></span>
      <a href="<?= BASE_URL ?>/index.php" class="a-btn-site" target="_blank">🌐 Voir le site</a>
    </div>
  </header>

  <div class="a-content">
    <?php
    $flashMsgs = [
      'ok_pharmacie'=>['success','✅ Pharmacie enregistrée.'],
      'del_pharmacie'=>['success','🗑️ Pharmacie supprimée.'],
      'ok_medicament'=>['success','✅ Médicament enregistré.'],
      'del_medicament'=>['success','🗑️ Médicament supprimé.'],
      'ok_article'=>['success','✅ Article enregistré.'],
      'del_article'=>['success','🗑️ Article supprimé.'],
      'ok_mission'=>['success','✅ Mission enregistrée.'],
      'del_mission'=>['success','🗑️ Mission supprimée.'],
      'ok_statut'=>['success','✅ Statut mis à jour.'],
    ];
    $fm = $flashMsgs[$_GET['msg']??''] ?? null;
    if ($fm): ?>
    <div class="a-alert a-alert-<?= $fm[0] ?>"><?= $fm[1] ?></div>
    <?php endif; ?>
