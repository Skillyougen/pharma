<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Inscription — PharmaLink</title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/pharmalink-logo.png">
<style>
.login-page{min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg-section);padding:40px 20px}
.login-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow-md);overflow:hidden;width:100%;max-width:500px}
.login-top{background:var(--primary);padding:28px 36px;text-align:center}
.login-top img{height:60px;width:auto;margin:0 auto 10px;object-fit:contain;filter:brightness(0) invert(1)}
.login-top h2{color:var(--white);font-family:var(--font-serif);font-size:20px;margin-bottom:4px}
.login-top p{color:rgba(255,255,255,.75);font-size:13px}
.login-body{padding:28px 36px}
.login-error{background:var(--red-bg);color:var(--red-err);border-left:4px solid var(--red-err);padding:11px 16px;border-radius:var(--radius);font-size:13px;margin-bottom:18px;font-family:var(--font-sans)}
.login-success{background:var(--green-bg);color:var(--green-ok);border-left:4px solid var(--green-ok);padding:11px 16px;border-radius:var(--radius);font-size:13px;margin-bottom:18px}
.form-grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.login-footer{text-align:center;margin-top:18px;font-size:13px;color:var(--text-muted);font-family:var(--font-sans)}
.login-footer a{color:var(--primary);font-weight:600}
.login-footer a:hover{color:var(--accent)}
.btn-back{display:inline-flex;align-items:center;gap:6px;color:var(--text-muted);font-size:13px;text-decoration:none;margin-top:14px;font-family:var(--font-sans)}
.btn-back:hover{color:var(--primary)}
@media(max-width:480px){.form-grid2{grid-template-columns:1fr}}
</style>
</head>
<body>
<div class="login-page">
  <div>
    <div class="login-card">
      <div class="login-top">
        <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="PharmaLink">
        <h2>Créer un compte</h2>
        <p>Rejoignez PharmaLink pour accéder à tous nos services</p>
      </div>
      <div class="login-body">
        <?php if (!empty($error)): ?>
        <div class="login-error">⚠️ <?= Security::h($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
        <div class="login-success">✅ <?= Security::h($success) ?> <a href="<?= BASE_URL ?>/index.php?page=auth&action=login">Connectez-vous →</a></div>
        <?php endif; ?>

        <form method="post" action="">
          <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
          <div class="form-grid2">
            <div class="form-group">
              <label class="form-label">Prénom *</label>
              <input type="text" name="prenom" class="form-control" required value="<?= Security::h($_POST['prenom'] ?? '') ?>">
            </div>
            <div class="form-group">
              <label class="form-label">Nom *</label>
              <input type="text" name="nom" class="form-control" required value="<?= Security::h($_POST['nom'] ?? '') ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control" required value="<?= Security::h($_POST['email'] ?? '') ?>" placeholder="votre@email.com">
          </div>
          <div class="form-grid2">
            <div class="form-group">
              <label class="form-label">Mot de passe *</label>
              <input type="password" name="password" class="form-control" required placeholder="Min. 6 caractères">
            </div>
            <div class="form-group">
              <label class="form-label">Confirmer *</label>
              <input type="password" name="password2" class="form-control" required placeholder="Répéter">
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-full" style="font-size:14px;padding:12px;margin-top:4px">Créer mon compte →</button>
        </form>

        <div class="login-footer">
          <p>Déjà un compte ? <a href="<?= BASE_URL ?>/index.php?page=auth&action=login">Se connecter</a></p>
        </div>
      </div>
    </div>
    <div style="text-align:center;margin-top:14px">
      <a href="<?= BASE_URL ?>/index.php" class="btn-back">← Retour au site</a>
    </div>
  </div>
</div>
</body>
</html>
