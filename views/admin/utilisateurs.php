<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>👥 Comptes utilisateurs</h1>
    <p><?= count($utilisateurs) ?> compte(s) enregistré(s)</p>
  </div>
</div>

<div class="a-card">
  <div class="a-card-head">
    <form method="get" action="index.php" class="a-search-form" style="width:100%;margin:0">
      <input type="hidden" name="page" value="admin">
      <input type="hidden" name="action" value="utilisateurs">
      <div class="a-search-wrap">
        <span class="a-search-ico">🔍</span>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher par nom, email..." class="a-form-ctrl">
      </div>
      <button type="submit" class="a-btn a-btn-primary">Filtrer</button>
      <?php if ($search): ?><a href="<?= BASE_URL ?>/index.php?page=admin&action=utilisateurs" class="a-btn a-btn-outline">✕</a><?php endif; ?>
    </form>
  </div>

  <div class="a-table-wrap">
    <?php if (empty($utilisateurs)): ?>
    <div class="a-empty"><div class="ico">👤</div><p>Aucun utilisateur trouvé.</p></div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nom complet</th>
          <th>Email</th>
          <th>Téléphone</th>
          <th>Rôle</th>
          <th>Inscrit le</th>
          <th>Dernière connexion</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($utilisateurs as $u): ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $u['id'] ?></td>
        <td>
          <div style="display:flex;align-items:center;gap:10px">
            <div style="width:34px;height:34px;border-radius:50%;background:var(--primary-light);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">
              <?= mb_strtoupper(mb_substr($u['prenom'],0,1).mb_substr($u['nom'],0,1)) ?>
            </div>
            <div>
              <div style="font-weight:600"><?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?></div>
              <?php if ($u['login_attempts'] > 3): ?><div style="font-size:11px;color:var(--danger)">⚠️ <?= $u['login_attempts'] ?> tentatives échouées</div><?php endif; ?>
            </div>
          </div>
        </td>
        <td><?= htmlspecialchars($u['email']) ?></td>
        <td><?= htmlspecialchars($u['telephone'] ?? '—') ?></td>
        <td>
          <?php if ($u['role'] === 'admin'): ?>
            <span class="a-badge a-badge-success">🛡️ Admin</span>
          <?php else: ?>
            <span class="a-badge a-badge-gray">👤 Client</span>
          <?php endif; ?>
        </td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y', strtotime($u['created_at'])) ?></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= $u['last_login'] ? date('d/m/Y H:i', strtotime($u['last_login'])) : '—' ?></td>
        <td>
          <div class="a-actions">
            <?php if ($u['id'] !== (int)$_SESSION['user']['id']): ?>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=utilisateur_toggle&id=<?= $u['id'] ?>"
               class="a-btn a-btn-sm a-btn-outline"
               title="<?= $u['role']==='admin'?'Rétrograder en client':'Promouvoir admin' ?>"
               data-confirm="<?= $u['role']==='admin'?'Rétrograder cet admin en client ?':'Donner les droits admin à cet utilisateur ?' ?>">
              <?= $u['role']==='admin' ? '⬇️' : '⬆️' ?>
            </a>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=utilisateur_delete&id=<?= $u['id'] ?>"
               class="a-btn a-btn-sm a-btn-danger"
               data-confirm="Supprimer définitivement le compte de <?= htmlspecialchars($u['prenom'].' '.$u['nom']) ?> ?">
              🗑️
            </a>
            <?php else: ?>
            <span style="font-size:11px;color:var(--text-muted);padding:0 8px">Vous</span>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
