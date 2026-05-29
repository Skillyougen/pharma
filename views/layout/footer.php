</div><!-- /.main-wrapper -->

<!-- CONTACT STRIP -->
<div class="contact-strip">
  <div class="contact-strip-inner">
    <div class="c-item"><span class="c-ico">📞</span><div><div class="c-label">Urgences</div><div class="c-val">+237 6XX XXX XXX</div></div></div>
    <div class="c-item"><span class="c-ico">📧</span><div><div class="c-label">Email</div><div class="c-val">contact@pharmalink.cm</div></div></div>
    <div class="c-item"><span class="c-ico">📍</span><div><div class="c-label">Localisation</div><div class="c-val">Douala, Cameroun</div></div></div>
    <div class="c-item"><span class="c-ico">⏰</span><div><div class="c-label">Pharmacies de garde</div><div class="c-val">Disponibles 24h/24</div></div></div>
  </div>
</div>

<!-- FOOTER -->
<footer class="footer">
  <div class="footer-inner">
    <div>
      <div class="f-logo">💊 PharmaLink</div>
      <p class="f-desc">Votre annuaire pharmaceutique de référence à Douala. Trouvez rapidement les pharmacies ouvertes et les médicaments disponibles près de chez vous.</p>
      <form class="footer-newsletter" method="post" action="<?= BASE_URL ?>/index.php?page=newsletter">
        <input type="email" name="email" placeholder="Votre adresse email..." required>
        <button type="submit">S'abonner</button>
      </form>
    </div>
    <div class="footer-col">
      <h4>Navigation</h4>
      <a href="<?= BASE_URL ?>/index.php">Accueil</a>
      <a href="<?= BASE_URL ?>/index.php?page=pharmacie">Pharmacies</a>
      <a href="<?= BASE_URL ?>/index.php?page=medicament">Médicaments</a>
      <a href="<?= BASE_URL ?>/index.php?page=blog">Blog Santé</a>
      <a href="<?= BASE_URL ?>/index.php?page=don">Faire un don</a>
    </div>
    <div class="footer-col">
      <h4>Services</h4>
      <a href="<?= BASE_URL ?>/index.php?page=pharmacie">Trouver une pharmacie</a>
      <a href="<?= BASE_URL ?>/index.php?page=medicament">Catalogue médicaments</a>
      <a href="<?= BASE_URL ?>/index.php?page=commande&action=panier">Mon panier</a>
      <a href="<?= BASE_URL ?>/index.php?page=auth&action=login">Espace client</a>
    </div>
    <div class="footer-col">
      <h4>Informations</h4>
      <a href="<?= BASE_URL ?>/index.php?page=contact">Contact</a>
      <a href="<?= BASE_URL ?>/index.php?page=pages&action=rgpd">RGPD / Confidentialité</a>
      <a href="<?= BASE_URL ?>/sitemap.php">Plan du site</a>
      <div style="margin-top:16px">
        <p style="color:#999;font-size:12px;margin-bottom:8px">Paiements acceptés :</p>
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
          <img src="<?= BASE_URL ?>/public/img/mtn-momo.png" alt="MTN MoMo" style="height:28px;width:auto;object-fit:contain">
          <img src="<?= BASE_URL ?>/public/img/orange-money.png" alt="Orange Money" style="height:28px;width:auto;object-fit:contain">
        </div>
      </div>
    </div>
  </div>
</footer>
<div class="footer-bottom">
  <span>© <?= date('Y') ?> PharmaLink — Tous droits réservés</span>
  <span><a href="<?= BASE_URL ?>/index.php?page=pages&action=rgpd">Politique de confidentialité</a></span>
</div>

<!-- Scroll to top -->
<a href="#" id="return-to-top" style="display:flex">↑</a>

<script src="<?= BASE_URL ?>/public/js/main.js"></script>
</body>
</html>
