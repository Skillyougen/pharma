<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>⚙️ Mon Profil</h1>
    <p>Modifier vos informations et mot de passe</p>
  </div>
</div>

<?php
$pdo  = getPDO();
$uid  = $_SESSION['user']['id'];
$user = $pdo->prepare("SELECT * FROM utilisateurs WHERE id=?");
$user->execute([$uid]);
$user = $user->fetch();
$msg  = ''; $err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!Security::checkCsrf()) { $err = 'Token invalide.'; }
    else {
        $type = $_POST['type'] ?? '';

        if ($type === 'infos') {
            $nom    = Security::sanitize($_POST['nom'] ?? '');
            $prenom = Security::sanitize($_POST['prenom'] ?? '');
            $email  = Security::sanitize($_POST['email'] ?? '');
            $tel    = Security::sanitize($_POST['telephone'] ?? '');

            // Check email uniqueness
            $chk = $pdo->prepare("SELECT id FROM utilisateurs WHERE email=? AND id!=?");
            $chk->execute([$email, $uid]);
            if ($chk->fetch()) {
                $err = 'Cet email est déjà utilisé par un autre compte.';
            } else {
                $pdo->prepare("UPDATE utilisateurs SET nom=?,prenom=?,email=?,telephone=? WHERE id=?")
                    ->execute([$nom, $prenom, $email, $tel, $uid]);
                $_SESSION['user']['nom']    = $nom;
                $_SESSION['user']['prenom'] = $prenom;
                $_SESSION['user']['email']  = $email;
                $msg = 'Informations mises à jour avec succès !';
                $user['nom']=$nom; $user['prenom']=$prenom; $user['email']=$email; $user['telephone']=$tel;
            }
        }

        if ($type === 'password') {
            $old  = $_POST['old_password'] ?? '';
            $new  = $_POST['new_password'] ?? '';
            $new2 = $_POST['new_password2'] ?? '';

            if (!password_verify($old, $user['mot_de_passe'])) {
                $err = 'Mot de passe actuel incorrect.';
            } elseif (strlen($new) < 6) {
                $err = 'Le nouveau mot de passe doit faire au moins 6 caractères.';
            } elseif ($new !== $new2) {
                $err = 'Les deux nouveaux mots de passe ne correspondent pas.';
            } else {
                $hash = password_hash($new, PASSWORD_BCRYPT);
                $pdo->prepare("UPDATE utilisateurs SET mot_de_passe=? WHERE id=?")->execute([$hash, $uid]);
                $msg = 'Mot de passe modifié avec succès !';
            }
        }
    }
}
?>

<?php if ($msg): ?><div class="a-alert a-alert-success">✅ <?= htmlspecialchars($msg) ?></div><?php endif; ?>
<?php if ($err): ?><div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($err) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

  <!-- Infos personnelles -->
  <div class="a-card">
    <div class="a-card-head"><h2>👤 Informations personnelles</h2></div>
    <div class="a-card-body">
      <form method="post">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
        <input type="hidden" name="type" value="infos">

        <div class="a-form-group">
          <label class="a-form-label">Prénom <span class="required">*</span></label>
          <input type="text" name="prenom" class="a-form-ctrl" required
                 value="<?= htmlspecialchars($user['prenom']) ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Nom <span class="required">*</span></label>
          <input type="text" name="nom" class="a-form-ctrl" required
                 value="<?= htmlspecialchars($user['nom']) ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Email <span class="required">*</span></label>
          <input type="email" name="email" class="a-form-ctrl" required
                 value="<?= htmlspecialchars($user['email']) ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Téléphone</label>
          <input type="text" name="telephone" class="a-form-ctrl"
                 value="<?= htmlspecialchars($user['telephone'] ?? '') ?>">
        </div>

        <!-- Rôle (readonly) -->
        <div class="a-form-group">
          <label class="a-form-label">Rôle</label>
          <div style="padding:9px 13px;background:var(--bg);border:1px solid var(--border);border-radius:8px;font-size:13px">
            <span class="a-badge a-badge-success">🛡️ Administrateur</span>
          </div>
        </div>

        <button type="submit" class="a-btn a-btn-primary">💾 Mettre à jour</button>
      </form>
    </div>
  </div>

  <!-- Changer le mot de passe -->
  <div class="a-card">
    <div class="a-card-head"><h2>🔒 Changer le mot de passe</h2></div>
    <div class="a-card-body">
      <form method="post">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
        <input type="hidden" name="type" value="password">

        <div class="a-form-group">
          <label class="a-form-label">Mot de passe actuel <span class="required">*</span></label>
          <input type="password" name="old_password" class="a-form-ctrl" required placeholder="••••••••">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Nouveau mot de passe <span class="required">*</span></label>
          <input type="password" name="new_password" class="a-form-ctrl" required placeholder="min. 6 caractères">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Confirmer le nouveau <span class="required">*</span></label>
          <input type="password" name="new_password2" class="a-form-ctrl" required placeholder="••••••••">
        </div>

        <div style="background:var(--warning-light);border:1px solid #fcd34d;border-radius:8px;padding:12px;margin-bottom:18px;font-size:12.5px;color:var(--warning)">
          ⚠️ Après modification, vous serez redirigé vers la page de connexion.
        </div>

        <button type="submit" class="a-btn a-btn-danger">🔒 Changer le mot de passe</button>
      </form>
    </div>

    <!-- Stats du compte -->
    <div class="a-card-head" style="border-top:1px solid var(--border)"><h2>📊 Informations du compte</h2></div>
    <div class="a-card-body">
      <div style="display:flex;flex-direction:column;gap:12px">
        <div style="display:flex;justify-content:space-between;font-size:13px">
          <span style="color:var(--text-muted)">Membre depuis</span>
          <strong><?= date('d/m/Y', strtotime($user['created_at'])) ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px">
          <span style="color:var(--text-muted)">Dernière connexion</span>
          <strong><?= $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'N/A' ?></strong>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px">
          <span style="color:var(--text-muted)">Email vérifié</span>
          <span class="badge <?= $user['email_verified'] ? 'badge-success' : 'badge-warning' ?>">
            <?= $user['email_verified'] ? '✅ Oui' : '⏳ Non' ?>
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:13px">
          <span style="color:var(--text-muted)">Rôle</span>
          <span class="a-badge a-badge-success">Administrateur</span>
        </div>
      </div>
    </div>
  </div>

</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
