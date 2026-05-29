<?php require BASE_PATH.'/views/layout/header.php'; ?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title">Catalogue Médicaments</h1>
</div>

<div class="section">
  <!-- Filtres -->
  <form method="get" action="<?= BASE_URL ?>/index.php" style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:20px 24px;margin-bottom:32px">
    <input type="hidden" name="page" value="medicament">
    <div style="display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end">
      <div style="flex:2;min-width:200px">
        <label class="form-label">Rechercher un médicament</label>
        <input type="text" name="q" class="form-control" value="<?= Security::h($q) ?>" placeholder="Nom, DCI...">
      </div>
      <div style="flex:1;min-width:160px">
        <label class="form-label">Catégorie</label>
        <select name="cat" class="form-control">
          <option value="">Toutes catégories</option>
          <?php foreach ($categories as $c): ?>
          <option value="<?= $c['id'] ?>" <?= $cat_id==$c['id']?'selected':'' ?>><?= Security::h(($c['icone']??'💊').' '.$c['nom']) ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <button type="submit" class="btn btn-primary">🔍 Filtrer</button>
        <?php if ($q || $cat_id): ?>
        <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn btn-outline" style="margin-left:6px">✕</a>
        <?php endif; ?>
      </div>
    </div>
  </form>

  <p style="color:var(--text-muted);margin-bottom:20px;font-family:var(--font-sans);font-size:13px"><strong><?= count($medicaments) ?></strong> médicament(s) trouvé(s)</p>

  <?php if (empty($medicaments)): ?>
  <div style="text-align:center;padding:60px">
    <div style="font-size:64px">💊</div>
    <p style="color:var(--text-muted);margin-top:12px">Aucun médicament trouvé.</p>
  </div>
  <?php else: ?>
  <div class="grid-3">
    <?php foreach ($medicaments as $m):
      $icones=['comprime'=>'💊','gelule'=>'💊','sirop'=>'🧴','injectable'=>'💉','pommade'=>'🫙','autre'=>'📦'];
    ?>
    <div class="card">
      <div class="card-header">
        <span style="font-size:48px"><?= $icones[$m['forme']] ?? '💊' ?></span>
        <?php if ($m['ordonnance']): ?><div style="position:absolute;top:10px;right:10px"><span class="badge badge-urgence">📋 Ordo.</span></div><?php endif; ?>
      </div>
      <div class="card-body">
        <div class="card-title"><?= Security::h($m['nom']) ?></div>
        <div class="card-info">
          <?php if ($m['dci']): ?><span style="font-size:12px">DCI : <?= Security::h($m['dci']) ?></span><br><?php endif; ?>
          <?php if ($m['dosage']): ?><span style="font-size:12px">Dosage : <?= Security::h($m['dosage']) ?></span><br><?php endif; ?>
          <?php if ($m['cat_nom']): ?><span class="badge-tag" style="margin-top:4px"><?= Security::h($m['cat_nom']) ?></span><?php endif; ?>
        </div>
      </div>
      <div class="card-footer">
        <span class="price"><?= number_format($m['prix'],0,',',' ') ?> FCFA</span>
        <div style="display:flex;gap:6px">
          <a href="<?= BASE_URL ?>/index.php?page=medicament&action=detail&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline">Détails</a>
          <form method="post" action="<?= BASE_URL ?>/index.php?page=commande&action=ajouter" style="display:inline">
            <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
            <input type="hidden" name="medicament_id" value="<?= $m['id'] ?>">
            <input type="hidden" name="quantite" value="1">
            <button type="submit" class="btn btn-sm btn-accent">🛒</button>
          </form>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php endif; ?>
</div>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
