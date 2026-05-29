<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="page-banner"><div class="page-banner-bg"></div><div class="page-banner-overlay"></div><h1 class="page-banner-title">Contactez-nous</h1></div>
<div class="section"><div class="grid-2" style="align-items:start;gap:40px">
  <div>
    <h2 style="font-family:var(--font-serif);margin-bottom:8px">Nous sommes là pour vous</h2>
    <div class="section-underline"></div>
    <?php if(!empty($ok)): ?><div class="flash flash--success"><span>✅ <?= Security::h($ok) ?></span></div><?php endif; ?>
    <?php if(!empty($err)): ?><div class="flash flash--error"><span>⚠️ <?= Security::h($err) ?></span></div><?php endif; ?>
    <form method="post" action="">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
      <div class="grid-2" style="gap:12px"><div class="form-group"><label class="form-label">Nom *</label><input type="text" name="nom" class="form-control" required></div><div class="form-group"><label class="form-label">Email *</label><input type="email" name="email" class="form-control" required></div></div>
      <div class="form-group"><label class="form-label">Téléphone</label><input type="text" name="telephone" class="form-control" placeholder="+237 6XX XXX XXX"></div>
      <div class="form-group"><label class="form-label">Sujet</label><input type="text" name="sujet" class="form-control"></div>
      <div class="form-group"><label class="form-label">Message *</label><textarea name="message" class="form-control" rows="5" required placeholder="Votre message..."></textarea></div>
      <button type="submit" class="btn btn-primary">Envoyer →</button>
    </form>
  </div>
  <div>
    <div class="contact-strip" style="border-radius:var(--radius-lg);padding:32px">
      <div style="display:flex;flex-direction:column;gap:24px">
        <div class="c-item"><span class="c-ico">📞</span><div><div class="c-label">Téléphone</div><div class="c-val">+237 6XX XXX XXX</div></div></div>
        <div class="c-item"><span class="c-ico">📧</span><div><div class="c-label">Email</div><div class="c-val">contact@pharmalink.cm</div></div></div>
        <div class="c-item"><span class="c-ico">📍</span><div><div class="c-label">Adresse</div><div class="c-val">Douala, Cameroun</div></div></div>
        <div class="c-item"><span class="c-ico">⏰</span><div><div class="c-label">Disponibilité</div><div class="c-val">Lun-Sam 8h-20h</div></div></div>
      </div>
    </div>
  </div>
</div></div>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
