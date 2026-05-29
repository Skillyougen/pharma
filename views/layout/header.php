<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'PharmaLink — Annuaire Pharmaceutique') ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/pharmalink-logo.png">
</head>
<body>

<!-- TOPBAR -->
<div class="topbar">
  <div>📍 <strong>Douala, Cameroun</strong> &nbsp;|&nbsp; ⏰ Urgences 24h/24 : <strong>+237 6XX XXX XXX</strong></div>
  <div class="topbar-right">
    <?php if (isset($_SESSION['user'])): ?>
      <span>👤 <?= Security::h($_SESSION['user']['prenom'].' '.$_SESSION['user']['nom']) ?></span>
      <?php if ($_SESSION['user']['role'] === 'admin'): ?>
        <a href="<?= BASE_URL ?>/index.php?page=admin">🛡️ Administration</a>
      <?php endif; ?>
      <a href="<?= BASE_URL ?>/index.php?page=auth&action=logout">Déconnexion</a>
    <?php else: ?>
      <a href="<?= BASE_URL ?>/index.php?page=auth&action=login">Connexion</a>
      <a href="<?= BASE_URL ?>/index.php?page=auth&action=register">Inscription</a>
    <?php endif; ?>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="navbar-inner">
    <!-- Logo -->
    <a href="<?= BASE_URL ?>/index.php" class="logo">
      <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="PharmaLink" style="height:52px;width:auto">
    </a>

    <!-- Liens nav -->
    <div class="nav-links" id="navLinks">
      <a href="<?= BASE_URL ?>/index.php" class="<?= ($page??'home')==='home'?'active':'' ?>">Accueil</a>
      <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="<?= ($page??'')==='pharmacie'?'active':'' ?>">Pharmacies</a>
      <a href="<?= BASE_URL ?>/index.php?page=medicament" class="<?= ($page??'')==='medicament'?'active':'' ?>">Médicaments</a>
      <a href="<?= BASE_URL ?>/index.php?page=blog" class="<?= ($page??'')==='blog'?'active':'' ?>">Blog Santé</a>
      <a href="<?= BASE_URL ?>/index.php?page=contact" class="<?= ($page??'')==='contact'?'active':'' ?>">Contact</a>
      <a href="<?= BASE_URL ?>/index.php?page=don" class="nav-don">❤️ Faire un don</a>
    </div>

    <!-- Actions -->
    <div class="nav-actions">
      <!-- Recherche rapide -->
      <form class="nav-search" method="get" action="<?= BASE_URL ?>/index.php">
        <input type="hidden" name="page" value="medicament">
        <input type="text" name="q" placeholder="Rechercher...">
        <button type="submit">🔍</button>
      </form>

      <!-- Panier -->
      <?php
      $nbPanier = 0;
      if (isset($_SESSION['panier'])) {
          foreach ($_SESSION['panier'] as $item) $nbPanier += $item['quantite'];
      }
      ?>
      <a href="<?= BASE_URL ?>/index.php?page=commande&action=panier" class="cart-btn">
        🛒
        <?php if ($nbPanier > 0): ?>
        <span class="cart-badge"><?= $nbPanier ?></span>
        <?php endif; ?>
      </a>
    </div>

    <!-- Burger mobile -->
    <button class="burger" id="burger" onclick="toggleNav()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<!-- Flash messages -->
<?php if (isset($_SESSION['flash'])): ?>
<div class="flash flash--<?= $_SESSION['flash']['type'] ?>">
  <span><?= Security::h($_SESSION['flash']['msg']) ?></span>
  <button class="flash-close" onclick="this.parentElement.remove()">×</button>
</div>
<?php unset($_SESSION['flash']); endif; ?>

<div class="main-wrapper">
