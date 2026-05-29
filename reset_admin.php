<?php
// ===================================================
// SCRIPT DE RESET ADMIN — SUPPRIMER APRÈS UTILISATION
// Accéder à : localhost/pharma_annuaire/reset_admin.php
// ===================================================
require_once __DIR__.'/config/database.php';

$email    = 'admin@gmail.com';
$password = 'admin123';
$hash     = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

$pdo = getPDO();
$pdo->prepare("DELETE FROM utilisateurs WHERE email=?")->execute([$email]);
$pdo->prepare("INSERT INTO utilisateurs (nom,prenom,email,mot_de_passe,role,email_verified,created_at) VALUES ('Admin','Super',?,?,'admin',1,NOW())")->execute([$email, $hash]);

$u = $pdo->prepare("SELECT * FROM utilisateurs WHERE email=?");
$u->execute([$email]);
$user = $u->fetch();
$ok   = $user && password_verify($password, $user['mot_de_passe']);
?>
<!DOCTYPE html><html><head><meta charset="UTF-8">
<style>body{font-family:sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;background:#f5f8fd;margin:0}
.box{max-width:500px;width:100%;background:#fff;border-radius:12px;box-shadow:0 4px 20px rgba(0,0,0,.1);overflow:hidden}
.top{background:#283b6a;color:#fff;padding:24px 28px}.top h2{font-size:20px;margin:0}
.body{padding:24px 28px}.row{display:flex;justify-content:space-between;padding:10px 0;border-bottom:1px solid #e5e7eb;font-size:14px}
.row:last-child{border:none}.lbl{color:#6b7280}.val{font-weight:600}
.ok{color:#16a34a;font-weight:700}.err{color:#dc2626;font-weight:700}
.btn{display:block;margin-top:20px;padding:12px 24px;background:#283b6a;color:#fff;border-radius:30px;text-decoration:none;text-align:center;font-weight:600;font-size:14px}
.btn:hover{background:#1e2d52}.warn{background:#fef3c7;border-left:4px solid #f59e0b;padding:10px 14px;border-radius:4px;font-size:12px;color:#92400e;margin-top:16px}
</style></head><body>
<div class="box">
  <div class="top"><h2>🔐 Reset Admin PharmaLink</h2></div>
  <div class="body">
    <div class="row"><span class="lbl">Email</span><span class="val"><?= htmlspecialchars($email) ?></span></div>
    <div class="row"><span class="lbl">Mot de passe</span><span class="val"><?= htmlspecialchars($password) ?></span></div>
    <div class="row"><span class="lbl">Hash généré</span><span class="val" style="font-size:10px;word-break:break-all"><?= htmlspecialchars($hash) ?></span></div>
    <div class="row"><span class="lbl">Vérification</span><span class="<?= $ok ? 'ok' : 'err' ?>"><?= $ok ? '✅ SUCCÈS — hash valide' : '❌ ERREUR' ?></span></div>
    <?php if ($ok): ?>
    <a href="/pharma_annuaire/index.php?page=auth&action=login" class="btn">→ Aller à la connexion admin</a>
    <div class="warn">⚠️ Supprimez ce fichier <strong>reset_admin.php</strong> après connexion !</div>
    <?php else: ?>
    <div class="warn" style="background:#fef2f2;border-color:#f87171;color:#991b1b">❌ Erreur inattendue. Vérifiez la BD.</div>
    <?php endif; ?>
  </div>
</div>
</body></html>
