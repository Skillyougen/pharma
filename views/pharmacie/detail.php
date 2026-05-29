<?php require BASE_PATH.'/views/layout/header.php'; ?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title"><?= Security::h($pharmacie['nom']) ?></h1>
</div>

<div class="section">
  <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="btn btn-sm btn-outline" style="margin-bottom:24px">← Toutes les pharmacies</a>

  <div style="display:grid;grid-template-columns:1fr 340px;gap:32px;margin-bottom:40px">
    <div>
      <div style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:20px">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:16px">
          <h2 style="font-family:var(--font-serif);font-size:22px"><?= Security::h($pharmacie['nom']) ?></h2>
          <?php if ($pharmacie['statut']==='ouvert'): ?><span class="badge badge-open" style="font-size:13px;padding:6px 14px">● Ouvert</span>
          <?php elseif ($pharmacie['statut']==='urgence'): ?><span class="badge badge-urgence" style="font-size:13px;padding:6px 14px">● Urgence 24h</span>
          <?php else: ?><span class="badge badge-closed" style="font-size:13px;padding:6px 14px">● Fermé</span><?php endif; ?>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
          <div><span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)">Adresse</span><p style="color:var(--text);margin-top:4px">📍 <?= Security::h($pharmacie['adresse']) ?></p></div>
          <?php if ($pharmacie['telephone']): ?><div><span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)">Téléphone</span><p style="color:var(--text);margin-top:4px">📞 <?= Security::h($pharmacie['telephone']) ?></p></div><?php endif; ?>
          <?php if ($pharmacie['horaires']): ?><div><span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)">Horaires</span><p style="color:var(--text);margin-top:4px">⏰ <?= Security::h($pharmacie['horaires']) ?></p></div><?php endif; ?>
          <?php if ($pharmacie['zone_nom']): ?><div><span style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:var(--text-muted)">Zone</span><p style="color:var(--text);margin-top:4px">🏘️ <?= Security::h($pharmacie['zone_nom']) ?></p></div><?php endif; ?>
        </div>
      </div>
    </div>

    <div>
      <div style="background:var(--primary);border-radius:var(--radius-lg);padding:24px;color:var(--white);text-align:center">
        <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="PharmaLink" style="height:72px;width:auto;margin:0 auto 16px;filter:brightness(0) invert(1)">
        <h3 style="font-family:var(--font-serif);color:var(--white);margin-bottom:8px"><?= Security::h($pharmacie['nom']) ?></h3>
        <p style="color:rgba(255,255,255,.8);font-size:13px;margin-bottom:16px"><?= count($stocks) ?> médicament(s) disponible(s)</p>
        <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn btn-accent btn-full">💊 Voir le catalogue</a>
      </div>
    </div>
  </div>

  <!-- Stocks médicaments -->
  <h2 style="font-family:var(--font-serif);font-size:22px;margin-bottom:8px">💊 Médicaments disponibles</h2>
  <div class="section-underline"></div>

  <?php if (empty($stocks)): ?>
  <div style="text-align:center;padding:40px;color:var(--text-muted)">
    <p>Aucun médicament répertorié pour cette pharmacie.</p>
  </div>
  <?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead>
        <tr>
          <th>Médicament</th>
          <th>DCI</th>
          <th>Forme</th>
          <th>Stock</th>
          <th>Prix</th>
          <th>Ordonnance</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($stocks as $s): ?>
        <tr>
          <td><strong><?= Security::h($s['med_nom']) ?></strong></td>
          <td style="color:var(--text-muted)"><?= Security::h($s['dci'] ?? '—') ?></td>
          <td><?php $f=['comprime'=>'💊','gelule'=>'💊','sirop'=>'🧴','injectable'=>'💉','pommade'=>'🫙'];echo ($f[$s['forme']]??'📦').' '.ucfirst($s['forme']); ?></td>
          <td>
            <?php $cls = $s['quantite']<10?'badge-closed':($s['quantite']<30?'badge-urgence':'badge-open'); ?>
            <span class="badge <?= $cls ?>"><?= $s['quantite'] ?> u.</span>
          </td>
          <td class="price"><?= number_format($s['prix'],0,',',' ') ?> F</td>
          <td><?= $s['ordonnance'] ? '<span class="badge badge-urgence">📋 Oui</span>' : '—' ?></td>
          <td>
            <form method="post" action="<?= BASE_URL ?>/index.php?page=commande&action=ajouter">
              <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
              <input type="hidden" name="medicament_id" value="<?= $s['medicament_id'] ?>">
              <input type="hidden" name="quantite" value="1">
              <button type="submit" class="btn btn-sm btn-accent" <?= $s['quantite']<1?'disabled':'' ?>>🛒 Commander</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
