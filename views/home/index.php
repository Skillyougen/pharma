<?php require BASE_PATH.'/views/layout/header.php'; ?>

<!-- HERO -->
<section class="hero">
  <div class="hero-bg" style="background-image:url('<?= BASE_URL ?>/public/img/hero.jpg')"></div>
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <span class="hero-badge">Annuaire Pharmaceutique — Douala</span>
    <h1 class="hero-title">Votre Santé,<br>Notre Priorité</h1>
    <p class="hero-sub">Trouvez en quelques secondes la pharmacie la plus proche, vérifiez la disponibilité des médicaments et passez commande en ligne.</p>
    <div class="hero-actions">
      <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="btn-hero-primary">🏥 Trouver une pharmacie</a>
      <a href="<?= BASE_URL ?>/index.php?page=medicament" class="btn-hero-outline">💊 Catalogue médicaments</a>
    </div>
  </div>
</section>

<!-- BARRE DE RECHERCHE -->
<div class="search-section">
  <form class="search-inner" method="get" action="<?= BASE_URL ?>/index.php">
    <input type="hidden" name="page" value="medicament">
    <input type="text" name="q" placeholder="Rechercher un médicament (nom, DCI, catégorie...)">
    <select name="cat">
      <option value="">Toutes catégories</option>
      <?php foreach ($categories as $c): ?>
      <option value="<?= $c['id'] ?>"><?= Security::h($c['icone'].' '.$c['nom']) ?></option>
      <?php endforeach; ?>
    </select>
    <button type="submit">🔍 Rechercher</button>
  </form>
</div>

<!-- STATS -->
<div class="stats-bar">
  <div class="stat-item"><div class="stat-num"><?= $stats['pharmacies'] ?></div><div class="stat-lbl">Pharmacies référencées</div></div>
  <div class="stat-item"><div class="stat-num"><?= $stats['medicaments'] ?></div><div class="stat-lbl">Médicaments disponibles</div></div>
  <div class="stat-item"><div class="stat-num"><?= $stats['ouvertes'] ?></div><div class="stat-lbl">Pharmacies ouvertes</div></div>
  <div class="stat-item"><div class="stat-num"><?= $stats['urgence'] ?></div><div class="stat-lbl">Urgences 24h/24</div></div>
  <div class="stat-item"><div class="stat-num"><?= $stats['zones'] ?></div><div class="stat-lbl">Zones couvertes</div></div>
</div>

<!-- PHARMACIES -->
<div class="section">
  <div class="section-header">
    <div>
      <h2 class="section-title">Pharmacies disponibles</h2>
      <div class="section-underline"></div>
    </div>
    <a href="<?= BASE_URL ?>/index.php?page=pharmacie" class="link-all">Voir toutes →</a>
  </div>
  <div class="grid-3">
    <?php foreach ($pharmacies as $p): ?>
    <div class="card">
      <div class="card-header">
        <img src="<?= BASE_URL ?>/public/img/pharmalink-logo.png" alt="Pharmacie" style="height:60px;width:auto;object-fit:contain">
        <div style="position:absolute;top:12px;right:12px">
          <?php if ($p['statut'] === 'ouvert'): ?>
            <span class="badge badge-open">● Ouvert</span>
          <?php elseif ($p['statut'] === 'urgence'): ?>
            <span class="badge badge-urgence">● Urgence 24h</span>
          <?php else: ?>
            <span class="badge badge-closed">● Fermé</span>
          <?php endif; ?>
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
        <?php if ($p['zone_nom']): ?><span style="font-size:12px;color:var(--text-muted)">📍 <?= Security::h($p['zone_nom']) ?></span><?php else: ?><span></span><?php endif; ?>
        <a href="<?= BASE_URL ?>/index.php?page=pharmacie&action=detail&id=<?= $p['id'] ?>" class="btn btn-sm btn-primary">Voir →</a>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- MÉDICAMENTS -->
<div style="background:var(--bg-section);padding:50px 0">
  <div class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Médicaments en stock</h2>
        <div class="section-underline"></div>
      </div>
      <a href="<?= BASE_URL ?>/index.php?page=medicament" class="link-all">Catalogue complet →</a>
    </div>
    <div class="grid-3">
      <?php foreach ($medicaments as $m): ?>
      <div class="card">
        <div class="card-header">
          <?php $icones = ['comprime'=>'💊','gelule'=>'💊','sirop'=>'🧴','injectable'=>'💉','pommade'=>'🫙','autre'=>'📦']; ?>
          <span style="font-size:48px"><?= $icones[$m['forme']] ?? '💊' ?></span>
          <?php if ($m['ordonnance']): ?>
          <div style="position:absolute;top:12px;right:12px"><span class="badge badge-urgence">📋 Ordo.</span></div>
          <?php endif; ?>
        </div>
        <div class="card-body">
          <div class="card-title"><?= Security::h($m['nom']) ?></div>
          <div class="card-info">
            <?php if ($m['dci']): ?>DCI : <?= Security::h($m['dci']) ?><br><?php endif; ?>
            <?php if ($m['cat_nom']): ?><span class="badge-tag"><?= Security::h($m['cat_nom']) ?></span><?php endif; ?>
          </div>
        </div>
        <div class="card-footer">
          <span class="price"><?= number_format($m['prix'], 0, ',', ' ') ?> FCFA</span>
          <a href="<?= BASE_URL ?>/index.php?page=medicament&action=detail&id=<?= $m['id'] ?>" class="btn btn-sm btn-outline">Détails →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- DON -->
<div class="section">
  <div class="grid-2" style="align-items:center;gap:48px">
    <div>
      <h2 class="section-title">Soutenez nos missions humanitaires</h2>
      <div class="section-underline"></div>
      <p style="margin-bottom:20px">Vos dons permettent d'approvisionner des communautés rurales en médicaments essentiels. Chaque contribution compte pour améliorer l'accès aux soins au Cameroun.</p>
      <ul style="list-style:none;margin-bottom:24px">
        <li style="display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:14px;color:var(--text-muted)">✅ Médicaments livrés dans les zones reculées</li>
        <li style="display:flex;align-items:center;gap:10px;margin-bottom:10px;font-size:14px;color:var(--text-muted)">✅ Sensibilisation à la santé publique</li>
        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text-muted)">✅ Transparence totale sur l'utilisation des fonds</li>
      </ul>
      <div style="display:flex;gap:12px;align-items:center">
        <img src="<?= BASE_URL ?>/public/img/mtn-momo.png" alt="MTN MoMo" style="height:40px;width:auto;object-fit:contain">
        <img src="<?= BASE_URL ?>/public/img/orange-money.png" alt="Orange Money" style="height:40px;width:auto;object-fit:contain">
        <img src="<?= BASE_URL ?>/public/img/especes.png" alt="Espèces" style="height:40px;width:auto;object-fit:contain">
      </div>
    </div>
    <div class="don-box">
      <div class="don-title">❤️ Faire un don</div>
      <p class="don-sub">Choisissez un montant et contribuez à notre mission de santé communautaire.</p>
      <form method="post" action="<?= BASE_URL ?>/index.php?page=don&action=traiter">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
        <div class="don-amounts">
          <?php foreach ([1000, 2000, 5000, 10000] as $m): ?>
          <button type="button" class="don-amount" onclick="setDon(<?= $m ?>)">
            <?= number_format($m, 0, ',', ' ') ?> F
          </button>
          <?php endforeach; ?>
        </div>
        <div class="form-group">
          <label class="form-label">Montant (FCFA)</label>
          <input type="number" name="montant" id="don-montant" class="form-control" min="500" placeholder="Autre montant..." required>
        </div>
        <div class="form-group">
          <label class="form-label">Votre nom (optionnel)</label>
          <input type="text" name="nom_donateur" class="form-control" placeholder="Anonyme si vide">
        </div>
        <div class="form-group">
          <label class="form-label">Méthode de paiement</label>
          <select name="methode_paiement" class="form-control">
            <option value="mtn_momo">📱 MTN Mobile Money</option>
            <option value="orange_money">🔶 Orange Money</option>
            <option value="especes">💵 Espèces</option>
          </select>
        </div>
        <p class="don-widget-rgpd">En faisant un don, vous acceptez notre <a href="<?= BASE_URL ?>/index.php?page=pages&action=rgpd">politique de confidentialité</a>.</p>
        <button type="submit" class="btn btn-primary btn-full">❤️ Confirmer mon don</button>
      </form>
    </div>
  </div>
</div>

<!-- BLOG -->
<?php if (!empty($articles)): ?>
<div style="background:var(--bg-section);padding:50px 0">
  <div class="section">
    <div class="section-header">
      <div>
        <h2 class="section-title">Blog Santé</h2>
        <div class="section-underline"></div>
      </div>
      <a href="<?= BASE_URL ?>/index.php?page=blog" class="link-all">Tous les articles →</a>
    </div>
    <div class="grid-3">
      <?php foreach ($articles as $a): ?>
      <div class="card">
        <div class="blog-card-img">📰</div>
        <div class="blog-card-body">
          <?php if ($a['tag']): ?><span class="badge-tag"><?= Security::h($a['tag']) ?></span><?php endif; ?>
          <div class="blog-card-title"><?= Security::h($a['titre']) ?></div>
          <?php if ($a['extrait']): ?><p style="font-size:13px;color:var(--text-muted)"><?= Security::h(mb_substr($a['extrait'], 0, 100)) ?>…</p><?php endif; ?>
        </div>
        <div class="blog-card-footer">
          <span>📅 <?= date('d/m/Y', strtotime($a['created_at'])) ?></span>
          <a href="<?= BASE_URL ?>/index.php?page=blog&action=detail&id=<?= $a['id'] ?>" class="blog-read">Lire →</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>
<?php endif; ?>

<?php require BASE_PATH.'/views/layout/footer.php'; ?>
<script>
function setDon(m) {
  document.getElementById('don-montant').value = m;
  document.querySelectorAll('.don-amount').forEach(b => b.classList.remove('selected'));
  event.target.classList.add('selected');
}
</script>
