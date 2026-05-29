<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>🏥 Pharmacies</h1>
    <p><?= count($pharmacies) ?> pharmacie(s) trouvée(s)</p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_add" class="a-btn a-btn-primary">+ Ajouter</a>
  </div>
</div>

<div class="a-card">
  <div class="a-card-head">
    <form method="get" action="index.php" class="a-search-form" style="width:100%;margin:0">
      <input type="hidden" name="page" value="admin">
      <input type="hidden" name="action" value="pharmacies">
      <div class="a-search-wrap">
        <span class="a-search-ico">🔍</span>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Rechercher une pharmacie..." class="a-form-ctrl">
      </div>
      <select name="zone" class="a-form-select" style="width:auto">
        <option value="">Toutes les zones</option>
        <?php foreach ($zones as $z): ?>
        <option value="<?= $z['id'] ?>" <?= $zone_id == $z['id'] ? 'selected' : '' ?>><?= htmlspecialchars($z['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="a-btn a-btn-primary">Filtrer</button>
      <?php if ($search || $zone_id): ?>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacies" class="a-btn a-btn-outline">✕ Reset</a>
      <?php endif; ?>
    </form>
  </div>

  <div class="a-table-wrap">
    <?php if (empty($pharmacies)): ?>
    <div class="a-empty">
      <div class="ico">🏥</div>
      <p>Aucune pharmacie trouvée.</p>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_add" class="a-btn a-btn-primary">Ajouter la première</a>
    </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Adresse</th>
          <th>Zone</th>
          <th>Téléphone</th>
          <th>Horaires</th>
          <th>Statut</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($pharmacies as $p): ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $p['id'] ?></td>
        <td><strong><?= htmlspecialchars($p['nom']) ?></strong></td>
        <td style="max-width:200px;color:var(--text-muted)"><?= htmlspecialchars($p['adresse']) ?></td>
        <td><?= htmlspecialchars($p['zone_nom'] ?? '—') ?></td>
        <td><?= htmlspecialchars($p['telephone'] ?? '—') ?></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= htmlspecialchars($p['horaires'] ?? '—') ?></td>
        <td>
          <?php $sb = ['ouvert'=>'success','ferme'=>'danger','urgence'=>'warning']; ?>
          <span class="badge badge-<?= $sb[$p['statut']] ?? 'gray' ?>">
            <?= $p['statut'] === 'ouvert' ? '🟢' : ($p['statut'] === 'urgence' ? '🟡' : '🔴') ?>
            <?= ucfirst($p['statut']) ?>
          </span>
        </td>
        <td>
          <div class="a-actions">
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_edit&id=<?= $p['id'] ?>" class="a-btn a-btn-sm a-btn-outline" title="Modifier">✏️</a>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_delete&id=<?= $p['id'] ?>&csrf_token=<?= Security::csrf() ?>"
               class="a-btn a-btn-sm a-btn-danger"
               data-confirm="Supprimer la pharmacie « <?= htmlspecialchars($p['nom']) ?> » ?"
               title="Supprimer">🗑️</a>
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
