<?php require BASE_PATH.'/views/layout/header.php'; ?>

<div class="page-banner">
  <div class="page-banner-bg"></div>
  <div class="page-banner-overlay"></div>
  <h1 class="page-banner-title">Finaliser la commande</h1>
</div>

<div class="section">
  <div class="cart-layout">
    <div>
      <form method="post" action="">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">

        <div style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:20px">
          <h3 style="font-family:var(--font-serif);margin-bottom:20px">🚚 Mode de livraison</h3>
          <div class="form-group">
            <select name="mode_livraison" class="form-control" id="modeLivraison" onchange="toggleAdresse(this.value)">
              <option value="retrait">🏪 Retrait en pharmacie</option>
              <option value="livraison">🚚 Livraison à domicile</option>
            </select>
          </div>
          <div id="adresseBox" class="form-group" style="display:none">
            <label class="form-label">Adresse de livraison</label>
            <input type="text" name="adresse_livraison" class="form-control" placeholder="Votre adresse complète...">
          </div>
          <div class="form-group">
            <label class="form-label">Note (optionnel)</label>
            <textarea name="note" class="form-control" rows="3" placeholder="Instructions spéciales..."></textarea>
          </div>
        </div>

        <div style="background:var(--bg-light);border:1px solid var(--border);border-radius:var(--radius-lg);padding:24px;margin-bottom:20px">
          <h3 style="font-family:var(--font-serif);margin-bottom:20px">💳 Mode de paiement</h3>
          <div class="payment-methods">
            <label class="payment-method selected" onclick="selectPay(this)">
              <input type="radio" name="mode_paiement" value="mtn_momo" checked style="display:none">
              <img src="<?= BASE_URL ?>/public/img/mtn-momo.png" alt="MTN MoMo">
              <div class="payment-method-label">MTN Mobile Money</div>
            </label>
            <label class="payment-method" onclick="selectPay(this)">
              <input type="radio" name="mode_paiement" value="orange_money" style="display:none">
              <img src="<?= BASE_URL ?>/public/img/orange-money.png" alt="Orange Money">
              <div class="payment-method-label">Orange Money</div>
            </label>
            <label class="payment-method" onclick="selectPay(this)">
              <input type="radio" name="mode_paiement" value="carte" style="display:none">
              <img src="<?= BASE_URL ?>/public/img/carte-bancaire.png" alt="Carte bancaire">
              <div class="payment-method-label">Carte bancaire</div>
            </label>
            <label class="payment-method" onclick="selectPay(this)">
              <input type="radio" name="mode_paiement" value="especes" style="display:none">
              <img src="<?= BASE_URL ?>/public/img/especes.png" alt="Espèces">
              <div class="payment-method-label">Espèces</div>
            </label>
          </div>
        </div>

        <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:14px">✅ Confirmer la commande</button>
      </form>
    </div>

    <!-- Résumé -->
    <div class="cart-summary">
      <h3 style="font-family:var(--font-serif);font-size:18px;margin-bottom:16px">Votre commande</h3>
      <?php foreach ($panier as $item): ?>
      <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:10px;padding-bottom:10px;border-bottom:1px solid var(--border)">
        <div>
          <div style="font-weight:600"><?= Security::h($item['nom']) ?></div>
          <div style="color:var(--text-muted)">×<?= $item['quantite'] ?></div>
        </div>
        <span style="font-weight:700;color:var(--primary)"><?= number_format($item['prix']*$item['quantite'],0,',',' ') ?> F</span>
      </div>
      <?php endforeach; ?>
      <div style="display:flex;justify-content:space-between;align-items:center;margin-top:12px">
        <span style="font-weight:700;font-size:14px">TOTAL</span>
        <div class="cart-total" style="font-size:24px"><?= number_format($total,0,',',' ') ?> F</div>
      </div>
    </div>
  </div>
</div>

<script>
function toggleAdresse(v) { document.getElementById('adresseBox').style.display = v==='livraison'?'block':'none'; }
function selectPay(el) {
  document.querySelectorAll('.payment-method').forEach(e=>e.classList.remove('selected'));
  el.classList.add('selected');
  el.querySelector('input[type=radio]').checked = true;
}
</script>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
