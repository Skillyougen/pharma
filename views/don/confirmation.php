<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="section"><div class="don-confirmation">
  <div class="don-confirm-icon">✅</div>
  <h2 style="font-family:var(--font-serif);color:var(--primary);margin-bottom:10px">Merci pour votre don !</h2>
  <p class="don-confirm-msg">Votre générosité contribue à améliorer l'accès aux médicaments au Cameroun.</p>
  <?php if ($don): ?>
  <div class="don-confirm-card">
    <div class="don-confirm-row"><span class="don-confirm-label">Référence</span><span class="don-confirm-val">#<?= $don['id'] ?></span></div>
    <div class="don-confirm-row"><span class="don-confirm-label">Montant</span><span class="don-confirm-montant"><?= number_format($don['montant'],0,',',' ') ?> FCFA</span></div>
    <div class="don-confirm-row"><span class="don-confirm-label">Méthode</span><span class="don-confirm-val"><?= Security::h($don['methode_paiement']) ?></span></div>
    <div class="don-confirm-row"><span class="don-confirm-label">Statut</span><span class="don-confirm-val">⏳ En attente de confirmation</span></div>
  </div>
  <div class="don-confirm-instructions">
    <h3>Instructions de paiement</h3>
    <?php if($don['methode_paiement']==='mtn_momo'): ?>
    <ol><li>Composez *126# sur votre téléphone MTN</li><li>Sélectionnez "Paiement marchand"</li><li>Entrez le montant : <?= number_format($don['montant'],0,',',' ') ?> FCFA</li><li>Numéro marchand : <strong>6XX XXX XXX</strong></li></ol>
    <?php elseif($don['methode_paiement']==='orange_money'): ?>
    <ol><li>Composez #150# sur votre téléphone Orange</li><li>Sélectionnez "Paiement"</li><li>Entrez le montant : <?= number_format($don['montant'],0,',',' ') ?> FCFA</li><li>Numéro : <strong>6XX XXX XXX</strong></li></ol>
    <?php else: ?><p>Contactez-nous au <strong>+237 6XX XXX XXX</strong> pour organiser le paiement.</p><?php endif; ?>
  </div>
  <?php endif; ?>
  <div class="don-confirm-actions">
    <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Retour à l'accueil</a>
    <a href="<?= BASE_URL ?>/index.php?page=don" class="btn btn-outline">Faire un autre don</a>
  </div>
</div></div>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
