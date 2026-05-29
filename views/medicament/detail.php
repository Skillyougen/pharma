<?php require BASE_PATH.'/views/layout/header.php';
$icones=['comprime'=>'💊','gelule'=>'💊','sirop'=>'🧴','injectable'=>'💉','pommade'=>'🫙','autre'=>'📦'];
?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title"><?= Security::h($medicament['nom']) ?></h1>
</div>

<div class="section">
  <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn btn-sm btn-outline" style="margin-bottom:24px">← Catalogue</a>

  <div style="display:grid;grid-template-columns:1fr 340px;gap:32px;margin-bottom:40px">
    <div>
      <div style="background:linear-gradient(135deg,var(--bg-section),#dce5f5);border-radius:var(--radius-lg);padding:32px;text-align:center;margin-bottom:20px;font-size:96px">
        <?= $icones[$medicament['forme']] ?? '💊' ?>
      </div>
      <?php if ($medicament['description']): ?>
      <div style="background:var(--bg-light);border-radius:var(--radius-lg);padding:24px;border:1px solid var(--border)">
        <h3 style="font-family:var(--font-serif);margin-bottom:12px">Description</h3>
        <p style="line-height:1.9"><?= nl2br(Security::h($medicament['description'])) ?></p>
      </div>
      <?php endif; ?>
    </div>

    <div>
      <div style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:16px">
        <h2 style="font-family:var(--font-serif);font-size:22px;margin-bottom:12px"><?= Security::h($medicament['nom']) ?></h2>
        <?php if ($medicament['cat_nom']): ?><span class="badge-tag"><?= Security::h($medicament['cat_nom']) ?></span><br><br><?php endif; ?>
        <?php if ($medicament['dci']): ?><div style="margin-bottom:8px;font-size:14px"><strong>DCI :</strong> <?= Security::h($medicament['dci']) ?></div><?php endif; ?>
        <?php if ($medicament['dosage']): ?><div style="margin-bottom:8px;font-size:14px"><strong>Dosage :</strong> <?= Security::h($medicament['dosage']) ?></div><?php endif; ?>
        <div style="margin-bottom:8px;font-size:14px"><strong>Forme :</strong> <?= ucfirst($medicament['forme']) ?></div>
        <?php if ($medicament['ordonnance']): ?><span class="badge badge-urgence">📋 Médicament sur ordonnance</span><br><br><?php endif; ?>
        <div style="font-family:var(--font-serif);font-size:32px;font-weight:700;color:var(--primary);margin:16px 0"><?= number_format($medicament['prix'],0,',',' ') ?> FCFA</div>
        <form method="post" action="<?= BASE_URL ?>/index.php?page=commande&action=ajouter">
          <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
          <input type="hidden" name="medicament_id" value="<?= $medicament['id'] ?>">
          <div style="display:flex;gap:8px;margin-bottom:12px">
            <input type="number" name="quantite" value="1" min="1" class="form-control" style="width:80px">
            <button type="submit" class="btn btn-accent" style="flex:1">🛒 Ajouter au panier</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Pharmacies qui l'ont -->
  <h2 style="font-family:var(--font-serif);font-size:22px;margin-bottom:8px">🏥 Disponible dans ces pharmacies</h2>
  <div class="section-underline"></div>
  <?php if (empty($stocks)): ?>
  <p style="color:var(--text-muted)">Ce médicament n'est pas encore référencé dans nos pharmacies partenaires.</p>
  <?php else: ?>
  <div class="table-wrap">
    <table class="table">
      <thead><tr><th>Pharmacie</th><th>Adresse</th><th>Statut</th><th>Stock</th><th>Téléphone</th></tr></thead>
      <tbody>
      <?php foreach ($stocks as $s): ?>
      <tr>
        <td><a href="<?= BASE_URL ?>/index.php?page=pharmacie&action=detail&id=<?= $s['pharmacie_id'] ?>" style="font-weight:600;color:var(--primary)"><?= Security::h($s['pharm_nom']) ?></a></td>
        <td style="color:var(--text-muted);font-size:13px">📍 <?= Security::h($s['adresse']) ?></td>
        <td><?php if ($s['pharm_statut']==='ouvert'): ?><span class="badge badge-open">Ouvert</span><?php elseif ($s['pharm_statut']==='urgence'): ?><span class="badge badge-urgence">Urgence</span><?php else: ?><span class="badge badge-closed">Fermé</span><?php endif; ?></td>
        <td><?php $cls=$s['quantite']<10?'badge-closed':($s['quantite']<30?'badge-urgence':'badge-open'); ?><span class="badge <?=$cls?>"><?=$s['quantite']?> u.</span></td>
        <td style="font-size:13px"><?= Security::h($s['telephone'] ?? '—') ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
