<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><title>Mot de passe oublié</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
<style>*{box-sizing:border-box;margin:0;padding:0}body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#0f4c2a,#16a34a);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}.wrap{max-width:420px;width:100%}.logo{text-align:center;margin-bottom:24px}.logo-icon{width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 10px}.logo h1{color:#fff;font-size:22px;font-weight:800}.card{background:#fff;border-radius:20px;padding:32px;box-shadow:0 24px 64px rgba(0,0,0,.25)}.card h2{font-size:20px;font-weight:700;color:#1e293b;margin-bottom:6px}.card p{color:#64748b;font-size:13px;margin-bottom:22px}.form-label{display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px}.form-control{width:100%;padding:10px 13px;border:1.5px solid #e2e8f0;border-radius:9px;font-size:14px;outline:none;transition:border-color .15s;font-family:inherit;margin-bottom:14px}.form-control:focus{border-color:#16a34a}.btn-submit{width:100%;padding:12px;background:linear-gradient(135deg,#16a34a,#15803d);color:#fff;border:none;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:inherit}.back{display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,.8);text-decoration:none;font-size:13px;margin-top:18px}</style>
</head><body>
<div class="wrap">
  <div class="logo"><div class="logo-icon">💊</div><h1>PharmaLink</h1></div>
  <div class="card">
    <h2>Mot de passe oublié</h2>
    <p>Entrez votre email pour recevoir un lien de réinitialisation.</p>
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
      <label class="form-label">Email</label>
      <input type="email" name="email" class="form-control" required placeholder="votre@email.com">
      <button type="submit" class="btn-submit">Envoyer le lien →</button>
    </form>
  </div>
  <a href="<?= BASE_URL ?>/index.php?page=auth&action=login" class="back">← Retour à la connexion</a>
</div>
</body></html>
