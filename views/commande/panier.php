<?php require BASE_PATH.'/views/layout/header.php'; ?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title">Mon Panier</h1>
</div>

<div class="section">
  <?php if (empty($panier)): ?>
  <div style="text-align:center;padding:80px 20px">
    <div style="font-size:80px;margin-bottom:20px">🛒</div>
    <h2 style="font-family:var(--font-serif);color:var(--text);margin-bottom:10px">Votre panier est vide</h2>
    <p style="color:var(--text-muted);margin-bottom:24px">Ajoutez des médicaments depuis notre catalogue.</p>
    <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn btn-primary">💊 Voir le catalogue</a>
  </div>
  <?php else:
    $total = array_sum(array_map(fn($i)=>$i['prix']*$i['quantite'],$panier));
  ?>
  <div class="cart-layout">
    <!-- Items -->
    <div>
      <div class="table-wrap">
        <table class="table">
          <thead><tr><th>Médicament</th><th>Prix unit.</th><th>Quantité</th><th>Sous-total</th><th>Action</th></tr></thead>
          <tbody>
          <?php foreach ($panier as $item): ?>
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px">
                <span style="font-size:32px">💊</span>
                <div>
                  <div style="font-weight:600"><?= Security::h($item['nom']) ?></div>
                  <a href="<?= BASE_URL ?>/index.php?page=medicament&action=detail&id=<?= $item['id'] ?>" style="font-size:12px;color:var(--text-muted)">Voir détails →</a>
                </div>
              </div>
            </td>
            <td class="price"><?= number_format($item['prix'],0,',',' ') ?> F</td>
            <td style="font-weight:600"><?= $item['quantite'] ?></td>
            <td class="price"><?= number_format($item['prix']*$item['quantite'],0,',',' ') ?> F</td>
            <td>
              <a href="<?= BASE_URL ?>/index.php?page=commande&action=retirer&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger">🗑️</a>
            </td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div style="margin-top:16px">
        <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn btn-outline">← Continuer mes achats</a>
      </div>
    </div>

    <!-- Résumé -->
    <div class="cart-summary">
      <h3 style="font-family:var(--font-serif);font-size:18px;margin-bottom:16px">Résumé de la commande</h3>
      <div style="border-top:1px solid var(--border);padding-top:16px">
        <?php foreach ($panier as $item): ?>
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:8px">
          <span><?= Security::h($item['nom']) ?> ×<?= $item['quantite'] ?></span>
          <span style="font-weight:600"><?= number_format($item['prix']*$item['quantite'],0,',',' ') ?> F</span>
        </div>
        <?php endforeach; ?>
      </div>
      <div style="border-top:2px solid var(--primary);margin-top:12px;padding-top:12px">
        <div style="display:flex;justify-content:space-between;align-items:center">
          <span style="font-weight:700;font-family:var(--font-sans)">TOTAL</span>
          <div class="cart-total"><?= number_format($total,0,',',' ') ?> F</div>
        </div>
      </div>
      <a href="<?= BASE_URL ?>/index.php?page=commande&action=checkout" class="btn btn-accent btn-full" style="margin-top:16px;font-size:15px;padding:14px">
        ✅ Commander maintenant
      </a>
      <p style="font-size:11px;color:var(--text-muted);text-align:center;margin-top:12px">Paiement sécurisé via MTN MoMo, Orange Money ou espèces</p>
      <div style="display:flex;gap:8px;justify-content:center;margin-top:8px">
        <img src="<?= BASE_URL ?>/public/img/mtn-momo.png" alt="MTN" style="height:28px;width:auto;object-fit:contain">
        <img src="<?= BASE_URL ?>/public/img/orange-money.png" alt="Orange" style="height:28px;width:auto;object-fit:contain">
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
