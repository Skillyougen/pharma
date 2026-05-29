<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="page-banner"><div class="page-banner-bg"></div><div class="page-banner-overlay"></div><h1 class="page-banner-title">Faire un Don</h1></div>
<div class="section" style="max-width:680px;margin:0 auto">
  <div class="don-box">
    <div class="don-title">❤️ Soutenez nos missions</div>
    <p class="don-sub">Vos dons permettent d'approvisionner des communautés en médicaments essentiels et de soutenir nos missions humanitaires au Cameroun.</p>
    <form method="post" action="<?= BASE_URL ?>/index.php?page=don&action=traiter">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
      <div class="don-amounts">
        <?php foreach ([500,1000,2000,5000,10000] as $m): ?>
        <button type="button" class="don-amount" onclick="setDon(<?=$m?>,this)"><?= number_format($m,0,',',' ') ?> F</button>
        <?php endforeach; ?>
      </div>
      <div class="form-group">
        <label class="form-label">Montant (FCFA) *</label>
        <input type="number" name="montant" id="don-montant" class="form-control" min="500" placeholder="Saisir ou choisir un montant..." required>
      </div>
      <div class="form-group">
        <label class="form-label">Votre nom (optionnel)</label>
        <input type="text" name="nom_donateur" class="form-control" placeholder="Anonyme si vide">
      </div>
      <div class="form-group">
        <label class="form-label">Email (optionnel)</label>
        <input type="email" name="email_donateur" class="form-control" placeholder="Pour recevoir un reçu">
      </div>
      <div class="form-group">
        <label class="form-label">Méthode de paiement</label>
        <div class="payment-methods">
          <label class="payment-method selected" onclick="selectPay(this)">
            <input type="radio" name="methode_paiement" value="mtn_momo" checked style="display:none">
            <img src="<?= BASE_URL ?>/public/img/mtn-momo.png" alt="MTN" style="height:36px;width:auto;object-fit:contain">
            <div class="payment-method-label">MTN MoMo</div>
          </label>
          <label class="payment-method" onclick="selectPay(this)">
            <input type="radio" name="methode_paiement" value="orange_money" style="display:none">
            <img src="<?= BASE_URL ?>/public/img/orange-money.png" alt="Orange" style="height:36px;width:auto;object-fit:contain">
            <div class="payment-method-label">Orange Money</div>
          </label>
          <label class="payment-method" onclick="selectPay(this)">
            <input type="radio" name="methode_paiement" value="especes" style="display:none">
            <img src="<?= BASE_URL ?>/public/img/especes.png" alt="Espèces" style="height:36px;width:auto;object-fit:contain">
            <div class="payment-method-label">Espèces</div>
          </label>
        </div>
      </div>
      <p class="don-widget-rgpd">En faisant un don, vous acceptez notre <a href="<?= BASE_URL ?>/index.php?page=pages&action=rgpd">politique de confidentialité</a>.</p>
      <button type="submit" class="btn btn-accent btn-full" style="font-size:15px;padding:14px">❤️ Valider mon don</button>
    </form>
  </div>
</div>
<script>
function setDon(m,el){document.getElementById('don-montant').value=m;document.querySelectorAll('.don-amount').forEach(b=>b.classList.remove('selected'));el.classList.add('selected');}
function selectPay(el){document.querySelectorAll('.payment-method').forEach(e=>e.classList.remove('selected'));el.classList.add('selected');el.querySelector('input').checked=true;}
</script>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
