<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>🗂️ Catégories</h1><p><?= count($categories) ?> catégorie(s)</p></div>
</div>

<?php if ($error): ?><div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 380px;gap:20px">

  <!-- Liste -->
  <div class="a-card">
    <div class="a-card-head"><h2>📋 Liste des catégories</h2></div>
    <div class="a-table-wrap">
      <?php if (empty($categories)): ?>
      <div class="a-empty"><div class="ico">🗂️</div><p>Aucune catégorie.</p></div>
      <?php else: ?>
      <table>
        <thead><tr><th>#</th><th>Icône</th><th>Nom</th><th>Médicaments</th><th>Actions</th></tr></thead>
        <tbody>
        <?php foreach ($categories as $c): ?>
        <tr>
          <td style="color:var(--text-muted)"><?= $c['id'] ?></td>
          <td style="font-size:22px;text-align:center"><?= htmlspecialchars($c['icone'] ?? '💊') ?></td>
          <td><strong><?= htmlspecialchars($c['nom']) ?></strong></td>
          <td><span class="a-badge a-badge-navy"><?= $c['nb_meds'] ?> médicament(s)</span></td>
          <td>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=categorie_delete&id=<?= $c['id'] ?>"
               class="a-btn a-btn-sm a-btn-danger"
               data-confirm="Supprimer la catégorie « <?= htmlspecialchars($c['nom']) ?> » ?\nLes médicaments de cette catégorie ne seront pas supprimés.">🗑️</a>
          </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Formulaire ajout -->
  <div class="a-card" style="align-self:start">
    <div class="a-card-head"><h2>➕ Ajouter une catégorie</h2></div>
    <div class="a-card-body">
      <form method="post">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
        <div class="a-form-group">
          <label class="a-form-label">Nom <span class="required">*</span></label>
          <input type="text" name="nom" class="a-form-ctrl" required placeholder="ex: Antibiotiques">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Icône (emoji)</label>
          <input type="text" name="icone" class="a-form-ctrl" placeholder="ex: 💊" value="💊" maxlength="4">
          <div style="margin-top:8px;display:flex;gap:6px;flex-wrap:wrap">
            <?php foreach (['💊','🩺','❤️','🧴','💉','🩹','🦷','👁️','🫁','🧠','🦴','🌿'] as $e): ?>
            <button type="button" onclick="document.querySelector('[name=icone]').value='<?= $e ?>'"
                    style="font-size:20px;background:none;border:1px solid var(--border);border-radius:6px;padding:4px 8px;cursor:pointer"><?= $e ?></button>
            <?php endforeach; ?>
          </div>
        </div>
        <button type="submit" class="a-btn a-btn-primary" style="width:100%">➕ Ajouter</button>
      </form>
    </div>
  </div>

</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
