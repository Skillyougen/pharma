<?php require __DIR__ . '/layout_header.php';
$pdo = getPDO();

// Stocks avec filtres
$pharma_id = (int)($_GET['pharma'] ?? 0);
$search    = Security::sanitize($_GET['q'] ?? '');

$sql = "SELECT s.*, m.nom as med_nom, m.forme, m.prix, p.nom as pharm_nom
        FROM stocks s
        JOIN medicaments m ON s.medicament_id = m.id
        JOIN pharmacies p ON s.pharmacie_id = p.id
        WHERE 1=1";
$params = [];
if ($pharma_id) { $sql .= " AND s.pharmacie_id=?"; $params[] = $pharma_id; }
if ($search)    { $sql .= " AND (m.nom LIKE ? OR p.nom LIKE ?)"; $params[] = "%$search%"; $params[] = "%$search%"; }
$sql .= " ORDER BY s.quantite ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$stocks = $stmt->fetchAll();

$pharmacies = $pdo->query("SELECT id, nom FROM pharmacies ORDER BY nom")->fetchAll();
$stocksFaibles = array_filter($stocks, fn($s) => $s['quantite'] < 30);
?>

<div class="a-page-head">
  <div style="flex:1"><h1>📦 Gestion des Stocks</h1><p><?= count($stocks) ?> entrée(s) de stock</p></div>
</div>

<!-- Alertes stocks faibles -->
<?php if (!empty($stocksFaibles)): ?>
<div class="a-alert a-alert-warning">
  ⚠️ <strong><?= count($stocksFaibles) ?> médicament(s)</strong> ont un stock inférieur à 30 unités.
</div>
<?php endif; ?>

<!-- Filtres -->
<div class="a-card" style="margin-bottom:16px">
  <div class="a-card-body" style="padding:14px 20px">
    <form method="get" action="index.php" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <input type="hidden" name="page" value="admin">
      <input type="hidden" name="action" value="stocks">
      <div class="a-search-wrap" style="flex:1;min-width:200px">
        <span class="a-search-ico">🔍</span>
        <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" placeholder="Médicament, pharmacie..." class="a-form-ctrl">
      </div>
      <select name="pharma" class="a-form-select" style="width:auto">
        <option value="">Toutes les pharmacies</option>
        <?php foreach ($pharmacies as $p): ?>
        <option value="<?= $p['id'] ?>" <?= $pharma_id==$p['id']?'selected':'' ?>><?= htmlspecialchars($p['nom']) ?></option>
        <?php endforeach; ?>
      </select>
      <button type="submit" class="a-btn a-btn-primary">Filtrer</button>
      <?php if ($search || $pharma_id): ?><a href="?page=admin&action=stocks" class="a-btn a-btn-outline">✕</a><?php endif; ?>
    </form>
  </div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($stocks)): ?>
    <div class="a-empty"><div class="ico">📦</div><p>Aucun stock trouvé.</p></div>
    <?php else: ?>
    <table>
      <thead>
        <tr><th>Médicament</th><th>Pharmacie</th><th>Quantité</th><th>Niveau</th><th>Dernière MàJ</th></tr>
      </thead>
      <tbody>
      <?php foreach ($stocks as $s):
        $niv = $s['quantite'] < 10 ? ['danger','🔴 Critique'] : ($s['quantite'] < 30 ? ['warning','🟡 Faible'] : ['success','🟢 OK']);
      ?>
      <tr>
        <td>
          <strong><?= htmlspecialchars($s['med_nom']) ?></strong>
          <div style="font-size:11px;color:var(--text-muted)"><?= ucfirst($s['forme'] ?? '') ?> — <?= number_format($s['prix'],0,',',' ') ?> F</div>
        </td>
        <td><?= htmlspecialchars($s['pharm_nom']) ?></td>
        <td>
          <span style="font-size:18px;font-weight:800;color:<?= $s['quantite']<10?'var(--danger)':($s['quantite']<30?'var(--warning)':'var(--primary)') ?>">
            <?= $s['quantite'] ?>
          </span>
          <span style="font-size:12px;color:var(--text-muted)"> unités</span>
        </td>
        <td><span class="badge badge-<?= $niv[0] ?>"><?= $niv[1] ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= isset($s['updated_at']) ? date('d/m/Y',strtotime($s['updated_at'])) : '—' ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
