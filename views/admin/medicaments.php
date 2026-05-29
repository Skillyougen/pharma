<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>💊 Médicaments</h1>
    <p><?= count($medicaments) ?> médicament(s) dans la base de données</p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicament_add" class="a-btn a-btn-primary">+ Ajouter</a>
  </div>
</div>

<!-- Filtres -->
<div class="a-card" style="margin-bottom:16px">
  <div class="a-card-body" style="padding:14px 20px">
    <form method="get" action="index.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <input type="hidden" name="page" value="admin">
      <input type="hidden" name="action" value="medicaments">
      <div class="a-search-wrap" style="flex:1;min-width:200px">
        <span class="a-search-ico">🔍</span>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Nom, DCI..." class="a-form-ctrl">
      </div>
      <select name="cat" class="a-form-select" style="width:auto">
        <option value="">Toutes catégories</option>
        <?php foreach ($categories as $c): ?>
        <option value="<?= $c['id'] ?>" <?= $cat_id==$c['id']?'selected':'' ?>><?= htmlspecialchars($c['icone'].' '.$c['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <select name="pharma" class="a-form-select" style="width:auto" onchange="this.form.submit()">
        <option value="">Toutes pharmacies</option>
        <?php
        $pdo = getPDO();
        $pharmas = $pdo->query("SELECT id, nom FROM pharmacies ORDER BY nom")->fetchAll();
        $pharma_id = (int)($_GET['pharma'] ?? 0);
        foreach ($pharmas as $ph): ?>
        <option value="<?= $ph['id'] ?>" <?= $pharma_id==$ph['id']?'selected':'' ?>><?= htmlspecialchars($ph['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="a-btn a-btn-primary">Filtrer</button>
      <?php if ($search || $cat_id || $pharma_id): ?>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicaments" class="a-btn a-btn-outline">✕ Reset</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<?php
// Filter by pharmacy if selected
if ($pharma_id > 0) {
    $stmtS = $pdo->prepare("SELECT medicament_id, quantite FROM stocks WHERE pharmacie_id=?");
    $stmtS->execute([$pharma_id]);
    $stocksPharm = [];
    foreach ($stmtS->fetchAll() as $s) $stocksPharm[$s['medicament_id']] = $s['quantite'];
    $medicaments = array_filter($medicaments, fn($m) => isset($stocksPharm[$m['id']]));
}
?>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($medicaments)): ?>
    <div class="a-empty">
      <div class="ico">💊</div>
      <p>Aucun médicament trouvé.</p>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicament_add" class="a-btn a-btn-primary">Ajouter le premier</a>
    </div>
    <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Médicament</th>
          <th>DCI</th>
          <th>Catégorie</th>
          <th>Forme</th>
          <th>Prix</th>
          <th>Stock global</th>
          <?php if ($pharma_id): ?><th>Stock pharmacie</th><?php endif; ?>
          <th>Ordonnance</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($medicaments as $m): ?>
      <?php $stock = $m['stock_global']; ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $m['id'] ?></td>
        <td>
          <div style="font-weight:600"><?= htmlspecialchars($m['nom']) ?></div>
          <?php if ($m['dosage']): ?><div style="font-size:11px;color:var(--text-muted)"><?= htmlspecialchars($m['dosage']) ?></div><?php endif; ?>
        </td>
        <td style="font-size:13px;color:var(--text-muted)"><?= htmlspecialchars($m['dci'] ?? '—') ?></td>
        <td><span class="a-badge a-badge-navy"><?= htmlspecialchars($m['cat_nom'] ?? '—') ?></span></td>
        <td>
          <?php $formes = ['comprime'=>'💊','gelule'=>'💊','sirop'=>'🧴','injectable'=>'💉','pommade'=>'🫙','autre'=>'📦'];?>
          <span style="font-size:13px"><?= ($formes[$m['forme']] ?? '📦') . ' ' . ucfirst($m['forme']) ?></span>
        </td>
        <td><strong><?= number_format($m['prix'], 0, ',', ' ') ?> F</strong></td>
        <td>
          <?php
          $cls = $stock < 10 ? 'danger' : ($stock < 30 ? 'warning' : 'success');
          ?>
          <span class="badge badge-<?= $cls ?>"><?= $stock ?> u.</span>
        </td>
        <?php if ($pharma_id): ?>
        <td>
          <?php $sq = $stocksPharm[$m['id']] ?? 0; ?>
          <span class="badge badge-<?= $sq < 10 ? 'danger' : 'success' ?>"><?= $sq ?> u.</span>
        </td>
        <?php endif; ?>
        <td>
          <?= $m['ordonnance'] ? '<span class="a-badge a-badge-warning">📋 Oui</span>' : '<span class="a-badge a-badge-gray">Non</span>' ?>
        </td>
        <td>
          <div class="a-actions">
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicament_edit&id=<?= $m['id'] ?>" class="a-btn a-btn-sm a-btn-outline" title="Modifier">✏️</a>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicament_delete&id=<?= $m['id'] ?>"
               class="a-btn a-btn-sm a-btn-danger"
               data-confirm="Supprimer « <?= htmlspecialchars($m['nom']) ?> » ?"
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
