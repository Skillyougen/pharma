<?php require BASE_PATH.'/views/layout/header.php'; ?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title">Pharmacies de Douala</h1>
</div>

<div class="section">
  <!-- Filtres -->
  <form method="get" action="<?= BASE_URL ?>/index.php" style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px 24px;margin-bottom:32px">
    <input type="hidden" name="page" value="pharmacie">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div style="flex:2;min-width:200px">
        <label class="form-label">Rechercher</label>
        <input type="text" name="q" class="form-control" value="<?= Security::h($q) ?>" placeholder="Nom de la pharmacie, adresse...">
      </div>
      <div style="flex:1;min-width:150px">
        <label class="form-label">Zone</label>
        <select name="zone" class="form-control">
          <option value="">Toutes les zones</option>
          <?php foreach ($zones as $z): ?>
          <option value="<?= $z['id'] ?>" <?= $zone_id==$z['id']?'selected':'' ?>><?= Security::h($z['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div style="flex:1;min-width:150px">
        <label class="form-label">Statut</label>
        <select name="statut" class="form-control">
          <option value="">Tous</option>
          <option value="ouvert"  <?= $statut==='ouvert'?'selected':'' ?>>🟢 Ouvert</option>
          <option value="urgence" <?= $statut==='urgence'?'selected':'' ?>>🟡 Urgence 24h</option>
          <option value="ferme"   <?= $statut==='ferme'?'selected':'' ?>>🔴 Fermé</option>
        </select>
      </div>
      <div>
        <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
        <?php if ($q || $zone_id || $statut): ?>
        <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="btn btn-outline" style="margin-left:6px">✕ Reset</a>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <p style="color:var(--text-muted);margin-bottom:20px;font-family:var(--font-sans);font-size:13px"><strong><?= count($pharmacies) ?></strong> pharmacie(s) trouvée(s)</p>

  <?php if (empty($pharmacies)): ?>
  <div style="text-align:center;padding:60px 20px">
    <div style="font-size:64px;margin-bottom:16px">🏥</div>
    <h3 style="font-family:var(--font-serif);color:var(--text)">Aucune pharmacie trouvée</h3>
    <p style="color:var(--text-muted)">Essayez d'autres critères de recherche.</p>
    <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="btn btn-primary" style="margin-top:16px">Voir toutes</a>
  </div>
  <?php else: ?>
  <div class="grid-3">
    <?php foreach ($pharmacies as $p): ?>
    <div class="card">
      <div class="card-header">
        <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="Pharmacie" style="height:56px;width:auto;object-fit:contain">
        <div style="position:absolute;top:12px;right:12px">
          <?php if ($p['statut']==='ouvert'): ?><span class="badge badge-open">● Ouvert</span>
          <?php elseif ($p['statut']==='urgence'): ?><span class="badge badge-urgence">● Urgence 24h</span>
          <?php else: ?><span class="badge badge-closed">● Fermé</span><?php endif; ?>
        </div>
      </div>
      <div class="card-body">
        <div class="card-title"><?= Security::h($p['nom']) ?></div>
        <div class="card-info">
          📍 <?= Security::h($p['adresse']) ?><br>
          <?php if ($p['telephone']): ?>📞 <?= Security::h($p['telephone']) ?><br><?php endif; ?>
          <?php if ($p['horaires']): ?>⏰ <?= Security::h($p['horaires']) ?><?php endif; ?>
        </div>
      </div>
      <div class="card-footer">
        <span style="font-size:12px;color:var(--text-muted)"><?= $p['zone_nom'] ? '📍 '.Security::h($p['zone_nom']) : '' ?></span>
        <a href="<?= BASE_URL ?>/index.php?page=pharmacie&action=detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Voir →</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
