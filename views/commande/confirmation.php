<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="section" style="text-align:center;padding:80px 20px">
  <div style="font-size:80px;margin-bottom:20px">✅</div>
  <h2 style="font-family:var(--font-serif);color:var(--primary);margin-bottom:10px">Commande confirmée !</h2>
  <p style="color:var(--text-muted);max-width:480px;margin:0 auto 24px">Votre commande #<?= $id ?> a été enregistrée. Notre équipe vous contactera pour la suite du traitement.</p>
  <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Retour à l'accueil</a>
</div>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
