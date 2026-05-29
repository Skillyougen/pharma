<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= htmlspecialchars($pageTitle) ?></title>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
<link rel="icon" type="image/png" href="<?= BASE_URL ?>/public/img/pharmalink-logo.png">
<style>
.login-page{min-height:100vh;display:flex;align-items:center;justify-content:center;background:var(--bg-section);padding:40px 20px}
.login-card{background:var(--white);border-radius:var(--radius-lg);box-shadow:var(--shadow-md);overflow:hidden;width:100%;max-width:440px}
.login-top{background:var(--primary);padding:32px 36px;text-align:center}
.login-top img{height:64px;width:auto;margin:0 auto 12px;object-fit:contain}
.login-top h2{color:var(--white);font-family:var(--font-serif);font-size:22px;margin-bottom:4px}
.login-top p{color:rgba(255,255,255,.75);font-size:13px}
.login-body{padding:32px 36px}
.login-body .form-label{color:var(--text);font-size:11px}
.login-body .form-control{border:1.5px solid var(--border)}
.login-body .form-control:focus{border-color:var(--primary)}
.login-error{background:var(--red-bg);color:var(--red-err);border-left:4px solid var(--red-err);padding:12px 16px;border-radius:var(--radius);font-size:13px;margin-bottom:20px;font-family:var(--font-sans)}
.login-success{background:var(--green-bg);color:var(--green-ok);border-left:4px solid var(--green-ok);padding:12px 16px;border-radius:var(--radius);font-size:13px;margin-bottom:20px}
.login-footer{text-align:center;margin-top:20px;font-size:13px;color:var(--text-muted);font-family:var(--font-sans)}
.login-footer a{color:var(--primary);font-weight:600}
.login-footer a:hover{color:var(--accent)}
.btn-back{display:inline-flex;align-items:center;gap:6px;color:rgba(255,255,255,.8);font-size:13px;text-decoration:none;margin-top:12px;font-family:var(--font-sans)}
.btn-back:hover{color:var(--white)}
</style>
</head>
<body>
<div class="login-page">
  <div>
    <div class="login-card">
      <div class="login-top">
        <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="PharmaLink">
        <h2>Connexion</h2>
        <p>Accédez à votre espace PharmaLink</p>
      </div>
      <div class="login-body">
        <?php if ($error): ?>
        <div class="login-error">⚠️ <?= Security::h($error) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['registered'])): ?>
        <div class="login-success">✅ Compte créé avec succès ! Connectez-vous.</div>
        <?php endif; ?>

        <form method="post" action="">
          <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
          <div class="form-group">
            <label class="form-label">Adresse email</label>
            <input type="email" name="email" class="form-control" required autofocus
                   placeholder="votre@email.com"
                   value="<?= Security::h($_POST['email'] ?? '') ?>">
          </div>
          <div class="form-group">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control" required placeholder="••••••••">
          </div>
          <div style="text-align:right;margin-bottom:20px">
            <a href="<?= BASE_URL ?>/index.php?page=auth&action=forgot_password" style="font-size:12px;color:var(--text-muted)">Mot de passe oublié ?</a>
          </div>
          <button type="submit" class="btn btn-primary btn-full" style="font-size:14px;padding:12px">Se connecter →</button>
        </form>

        <div class="login-footer">
          <p>Pas encore de compte ? <a href="<?= BASE_URL ?>/index.php?page=auth&action=register">Créer un compte</a></p>
        </div>
      </div>
    </div>
    <div style="text-align:center;margin-top:16px">
      <a href="<?= BASE_URL ?>/index.php" class="btn-back">← Retour au site</a>
    </div>
  </div>
</div>
</body>
</html>
