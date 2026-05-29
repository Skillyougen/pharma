<?php require __DIR__ . '/layout_header.php';
$pdo = getPDO();
$pharmacies = $pdo->query("
    SELECT p.*, z.nom as zone_nom
    FROM pharmacies p
    LEFT JOIN zones z ON p.zone_id = z.id
    WHERE p.latitude IS NOT NULL AND p.longitude IS NOT NULL
    ORDER BY p.nom
")->fetchAll();

$pharmaciesJson = json_encode(array_map(fn($p) => [
    'id'       => $p['id'],
    'nom'      => $p['nom'],
    'adresse'  => $p['adresse'],
    'telephone'=> $p['telephone'] ?? '',
    'horaires' => $p['horaires'] ?? '',
    'statut'   => $p['statut'],
    'zone'     => $p['zone_nom'] ?? '',
    'lat'      => (float)$p['latitude'],
    'lng'      => (float)$p['longitude'],
], $pharmacies));

// Stats rapides
$total = (int)$pdo->query("SELECT COUNT(*) FROM pharmacies")->fetchColumn();
$ouvertes = (int)$pdo->query("SELECT COUNT(*) FROM pharmacies WHERE statut='ouvert'")->fetchColumn();
$urgence  = (int)$pdo->query("SELECT COUNT(*) FROM pharmacies WHERE statut='urgence'")->fetchColumn();
$avecGPS  = count($pharmacies);
?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>🗺️ Carte des Pharmacies</h1>
    <p><?= $avecGPS ?> pharmacie(s) géolocalisée(s) sur <?= $total ?> au total</p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_add" class="a-btn a-btn-primary">+ Ajouter une pharmacie</a>
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacies" class="a-btn a-btn-outline">📋 Liste</a>
  </div>
</div>

<!-- Mini stats -->
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-bottom:20px">
  <div class="a-stat" style="padding:14px">
    <div class="stat-icon green" style="width:40px;height:40px;font-size:18px">🏥</div>
    <div class="stat-info"><div class="a-stat-val" style="font-size:22px"><?= $total ?></div><div class="a-stat-lbl">Total</div></div>
  </div>
  <div class="a-stat" style="padding:14px">
    <div class="stat-icon green" style="width:40px;height:40px;font-size:18px">🟢</div>
    <div class="stat-info"><div class="a-stat-val" style="font-size:22px"><?= $ouvertes ?></div><div class="a-stat-lbl">Ouvertes</div></div>
  </div>
  <div class="a-stat" style="padding:14px">
    <div class="stat-icon orange" style="width:40px;height:40px;font-size:18px">🟡</div>
    <div class="stat-info"><div class="a-stat-val" style="font-size:22px"><?= $urgence ?></div><div class="a-stat-lbl">Urgence 24h</div></div>
  </div>
  <div class="a-stat" style="padding:14px">
    <div class="stat-icon blue" style="width:40px;height:40px;font-size:18px">📍</div>
    <div class="stat-info"><div class="a-stat-val" style="font-size:22px"><?= $avecGPS ?></div><div class="a-stat-lbl">Géolocalisées</div></div>
  </div>
</div>

<div style="display:grid;grid-template-columns:320px 1fr;gap:16px;height:600px">

  <!-- Sidebar liste -->
  <div class="a-card" style="overflow:hidden;display:flex;flex-direction:column">
    <div class="a-card-head" style="flex-shrink:0">
      <h2>📋 Pharmacies</h2>
      <input type="text" id="searchMap" placeholder="Rechercher..." class="a-form-ctrl" style="width:140px;font-size:12px;padding:5px 10px">
    </div>
    <div id="pharmaciesList" style="overflow-y:auto;flex:1">
      <?php foreach ($pharmacies as $p): ?>
      <?php $colors = ['ouvert'=>'#16a34a','ferme'=>'#dc2626','urgence'=>'#d97706']; ?>
      <div class="pharma-item" data-id="<?= $p['id'] ?>" data-lat="<?= $p['latitude'] ?>" data-lng="<?= $p['longitude'] ?>"
           data-nom="<?= strtolower($p['nom']) ?>"
           onclick="flyTo(<?= $p['latitude'] ?>, <?= $p['longitude'] ?>, <?= $p['id'] ?>)"
           style="padding:12px 16px;border-bottom:1px solid var(--border);cursor:pointer;transition:background .1s">
        <div style="display:flex;align-items:flex-start;gap:8px">
          <div style="width:8px;height:8px;border-radius:50%;background:<?= $colors[$p['statut']] ?? '#94a3b8' ?>;margin-top:5px;flex-shrink:0"></div>
          <div style="flex:1;min-width:0">
            <div style="font-weight:600;font-size:13px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?= htmlspecialchars($p['nom']) ?></div>
            <div style="font-size:11px;color:var(--text-muted);margin-top:2px"><?= htmlspecialchars($p['adresse']) ?></div>
            <?php if ($p['horaires']): ?><div style="font-size:11px;color:var(--primary);margin-top:2px">⏰ <?= htmlspecialchars($p['horaires']) ?></div><?php endif; ?>
          </div>
          <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_edit&id=<?= $p['id'] ?>"
             onclick="event.stopPropagation()" style="font-size:12px;color:var(--text-muted);text-decoration:none;flex-shrink:0">✏️</a>
        </div>
      </div>
      <?php endforeach; ?>
      <?php if (empty($pharmacies)): ?>
      <div style="padding:30px;text-align:center;color:var(--text-muted)">
        <p>Aucune pharmacie géolocalisée.</p>
        <p style="font-size:12px;margin-top:8px">Ajoutez des coordonnées GPS depuis la liste.</p>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <!-- Map -->
  <div class="a-card" style="overflow:hidden;padding:0">
    <div id="map" style="width:100%;height:100%;border-radius:var(--radius)"></div>
  </div>

</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
.pharma-item:hover { background: var(--primary-xlight); }
.pharma-item.active { background: var(--primary-light); border-left: 3px solid var(--primary); }
.leaflet-popup-content-wrapper { border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.15); }
.popup-content { font-family: 'Inter', sans-serif; padding: 4px 0; }
.popup-content h3 { font-size: 14px; font-weight: 700; margin-bottom: 6px; color: #1e293b; }
.popup-content p { font-size: 12px; color: #64748b; margin-bottom: 3px; }
.popup-badge { display: inline-block; padding: 2px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; margin-bottom: 8px; }
.popup-btn { display: inline-block; padding: 5px 12px; background: #16a34a; color: #fff; border-radius: 6px; font-size: 12px; text-decoration: none; margin-top: 6px; }
</style>

<script>
const pharmaciesData = <?= $pharmaciesJson ?>;

// Centre sur Douala
const map = L.map('map').setView([4.0511, 9.7085], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '© OpenStreetMap contributors',
  maxZoom: 19
}).addTo(map);

const icons = {
  ouvert:  L.divIcon({ html: '<div style="background:#16a34a;width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.3)"></div>', className:'', iconSize:[14,14] }),
  ferme:   L.divIcon({ html: '<div style="background:#dc2626;width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.3)"></div>', className:'', iconSize:[14,14] }),
  urgence: L.divIcon({ html: '<div style="background:#d97706;width:14px;height:14px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,.3)"></div>', className:'', iconSize:[14,14] }),
};

const markers = {};
const badgeColors = { ouvert:'background:#dcfce7;color:#16a34a', ferme:'background:#fef2f2;color:#dc2626', urgence:'background:#fffbeb;color:#d97706' };

pharmaciesData.forEach(p => {
  if (!p.lat || !p.lng) return;
  const marker = L.marker([p.lat, p.lng], { icon: icons[p.statut] || icons.ouvert });
  const label = p.statut === 'ouvert' ? '🟢 Ouvert' : p.statut === 'urgence' ? '🟡 Urgence' : '🔴 Fermé';
  marker.bindPopup(`
    <div class="popup-content">
      <span class="popup-badge" style="${badgeColors[p.statut]||''}">${label}</span>
      <h3>${p.nom}</h3>
      <p>📍 ${p.adresse}</p>
      ${p.telephone ? `<p>📞 ${p.telephone}</p>` : ''}
      ${p.horaires ? `<p>⏰ ${p.horaires}</p>` : ''}
      ${p.zone ? `<p>🏘️ ${p.zone}</p>` : ''}
      <a class="popup-btn" href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_edit&id=${p.id}">✏️ Modifier</a>
    </div>
  `, { maxWidth: 260 });
  marker.addTo(map);
  markers[p.id] = marker;
});

function flyTo(lat, lng, id) {
  map.flyTo([lat, lng], 16, { animate: true, duration: 0.8 });
  if (markers[id]) markers[id].openPopup();
  document.querySelectorAll('.pharma-item').forEach(el => el.classList.remove('active'));
  document.querySelector(`.pharma-item[data-id="${id}"]`)?.classList.add('active');
}

// Search filter
document.getElementById('searchMap').addEventListener('input', function() {
  const q = this.value.toLowerCase();
  document.querySelectorAll('.pharma-item').forEach(el => {
    el.style.display = el.dataset.nom.includes(q) ? '' : 'none';
  });
});
</script>

<?php require __DIR__ . '/layout_footer.php'; ?>
