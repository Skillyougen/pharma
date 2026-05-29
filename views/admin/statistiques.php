<?php require __DIR__ . '/layout_header.php';
$pdo = getPDO();

// Période
$periode = $_GET['periode'] ?? '30';
$periode = in_array($periode, ['7','30','90','365']) ? (int)$periode : 30;

// Commandes par jour sur la période
$cmdParJour = $pdo->prepare("
    SELECT DATE(created_at) as jour, COUNT(*) as nb, SUM(total) as ca
    FROM commandes
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL ? DAY)
    GROUP BY DATE(created_at)
    ORDER BY jour ASC
");
$cmdParJour->execute([$periode]);
$cmdData = $cmdParJour->fetchAll();

// Top médicaments commandés
$topMeds = $pdo->query("
    SELECT m.nom, SUM(cl.quantite) as total_qte, SUM(cl.quantite * cl.prix_unitaire) as ca
    FROM commande_lignes cl
    JOIN medicaments m ON cl.medicament_id = m.id
    GROUP BY cl.medicament_id
    ORDER BY total_qte DESC
    LIMIT 8
")->fetchAll();

// Commandes par statut
$cmdStatuts = $pdo->query("SELECT statut, COUNT(*) as nb FROM commandes GROUP BY statut")->fetchAll();
$totalCmd = array_sum(array_column($cmdStatuts, 'nb'));

// Utilisateurs inscrits par mois (6 derniers mois)
$usersParMois = $pdo->query("
    SELECT DATE_FORMAT(created_at,'%Y-%m') as mois, COUNT(*) as nb
    FROM utilisateurs
    WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY mois ORDER BY mois ASC
")->fetchAll();

// Connexions récentes (last_login)
$connexionsRecentes = $pdo->query("
    SELECT nom, prenom, email, role, last_login
    FROM utilisateurs
    WHERE last_login IS NOT NULL
    ORDER BY last_login DESC
    LIMIT 10
")->fetchAll();

// Taux global
$totalUtilisateurs = (int)$pdo->query("SELECT COUNT(*) FROM utilisateurs")->fetchColumn();
$totalConnectes    = (int)$pdo->query("SELECT COUNT(*) FROM utilisateurs WHERE last_login IS NOT NULL")->fetchColumn();
$totalAcheteurs    = (int)$pdo->query("SELECT COUNT(DISTINCT utilisateur_id) FROM commandes WHERE statut IN ('confirmee','livree') AND utilisateur_id IS NOT NULL")->fetchColumn();

// CA total
$caTotal = (float)$pdo->query("SELECT COALESCE(SUM(total),0) FROM commandes WHERE statut IN ('confirmee','livree')")->fetchColumn();

// Préparer données graphique JSON
$joursLabels = []; $joursCmd = []; $joursCA = [];
foreach ($cmdData as $d) {
    $joursLabels[] = date('d/m', strtotime($d['jour']));
    $joursCmd[]    = (int)$d['nb'];
    $joursCA[]     = (float)$d['ca'];
}
$moisLabels = []; $moisUsers = [];
foreach ($usersParMois as $u) {
    $moisLabels[] = $u['mois'];
    $moisUsers[]  = (int)$u['nb'];
}
?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>📈 Statistiques</h1>
    <p>Vue d'ensemble des performances sur <?= $periode ?> jours</p>
  </div>
  <div class="a-page-actions">
    <?php foreach (['7'=>'7 jours','30'=>'30 jours','90'=>'3 mois','365'=>'1 an'] as $p=>$l): ?>
    <a href="?page=admin&action=statistiques&periode=<?= $p ?>"
       class="btn <?= $periode==(int)$p?'btn-primary':'btn-outline' ?>"><?= $l ?></a>
    <?php endforeach; ?>
  </div>
</div>

<!-- KPIs -->
<div class="a-stats" style="grid-template-columns:repeat(4,1fr);margin-bottom:24px">
  <div class="a-stat">
    <div class="stat-icon green">🛒</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $totalCmd ?></div>
      <div class="a-stat-lbl">Total commandes</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon blue">💰</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= number_format($caTotal, 0, ',', ' ') ?></div>
      <div class="a-stat-lbl">CA confirmé (FCFA)</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon orange">👥</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $totalConnectes ?> / <?= $totalUtilisateurs ?></div>
      <div class="a-stat-lbl">Utilisateurs connectés</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon teal">🛍️</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $totalAcheteurs ?></div>
      <div class="a-stat-lbl">Acheteurs uniques</div>
    </div>
  </div>
</div>

<!-- Graphiques -->
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  <!-- Commandes par jour -->
  <div class="a-card">
    <div class="a-card-head"><h2>🛒 Commandes sur <?= $periode ?> jours</h2></div>
    <div class="a-card-body">
      <?php if (empty($cmdData)): ?>
      <div class="a-empty" style="padding:30px"><p>Aucune commande sur cette période.</p></div>
      <?php else: ?>
      <canvas id="chartCommandes" height="200"></canvas>
      <?php endif; ?>
    </div>
  </div>

  <!-- Inscriptions par mois -->
  <div class="a-card">
    <div class="a-card-head"><h2>👥 Inscriptions (6 mois)</h2></div>
    <div class="a-card-body">
      <?php if (empty($usersParMois)): ?>
      <div class="a-empty" style="padding:30px"><p>Aucune inscription.</p></div>
      <?php else: ?>
      <canvas id="chartUsers" height="200"></canvas>
      <?php endif; ?>
    </div>
  </div>

</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px">

  <!-- Top médicaments -->
  <div class="a-card">
    <div class="a-card-head"><h2>💊 Top médicaments commandés</h2></div>
    <div class="a-card-body">
      <?php if (empty($topMeds)): ?>
      <div class="a-empty" style="padding:30px"><p>Aucune donnée.</p></div>
      <?php else: ?>
      <?php $maxQ = max(array_column($topMeds,'total_qte')); ?>
      <?php foreach ($topMeds as $i => $m): ?>
      <div style="margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;margin-bottom:5px">
          <span style="font-size:13px;font-weight:500"><?= ($i+1) ?>. <?= htmlspecialchars($m['nom']) ?></span>
          <span style="font-size:12px;font-weight:700;color:var(--primary)"><?= $m['total_qte'] ?> vendus</span>
        </div>
        <div class="a-progress">
          <div class="a-progress-bar" style="width:<?= round($m['total_qte']/$maxQ*100) ?>%"></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <!-- Commandes par statut -->
  <div class="a-card">
    <div class="a-card-head"><h2>📊 Répartition des commandes</h2></div>
    <div class="a-card-body">
      <canvas id="chartStatuts" height="200"></canvas>
      <div style="margin-top:20px">
        <?php
        $sBadges = ['panier'=>['gray','🛒'],'en_attente'=>['warning','⏳'],'confirmee'=>['info','✅'],'livree'=>['success','📦'],'annulee'=>['danger','❌']];
        foreach ($cmdStatuts as $cs):
          $b = $sBadges[$cs['statut']] ?? ['gray','❓'];
          $pct = $totalCmd > 0 ? round($cs['nb']/$totalCmd*100) : 0;
        ?>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;font-size:13px">
          <span><?= $b[1] ?> <?= ucfirst($cs['statut']) ?></span>
          <span><strong><?= $cs['nb'] ?></strong> <span style="color:var(--text-muted)">(<?= $pct ?>%)</span></span>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</div>

<!-- Dernières connexions -->
<div class="a-card">
  <div class="a-card-head">
    <h2>🔐 Dernières connexions</h2>
    <span style="font-size:12px;color:var(--text-muted)"><?= $totalConnectes ?> utilisateurs s'étaient déjà connectés</span>
  </div>
  <div class="a-table-wrap">
    <?php if (empty($connexionsRecentes)): ?>
    <div class="a-empty" style="padding:30px"><p>Aucune connexion enregistrée.</p></div>
    <?php else: ?>
    <table>
      <thead><tr><th>Utilisateur</th><th>Email</th><th>Rôle</th><th>Dernière connexion</th></tr></thead>
      <tbody>
      <?php foreach ($connexionsRecentes as $c): ?>
      <tr>
        <td>
          <div style="display:flex;align-items:center;gap:8px">
            <div style="width:30px;height:30px;border-radius:50%;background:var(--primary-light);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-weight:700;font-size:11px">
              <?= mb_strtoupper(mb_substr($c['prenom'],0,1).mb_substr($c['nom'],0,1)) ?>
            </div>
            <?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?>
          </div>
        </td>
        <td style="color:var(--text-muted)"><?= htmlspecialchars($c['email']) ?></td>
        <td><span class="badge badge-<?= $c['role']==='admin'?'success':'gray' ?>"><?= $c['role']==='admin'?'🛡️ Admin':'👤 Client' ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y H:i', strtotime($c['last_login'])) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const green = '#16a34a', greenLight = 'rgba(22,163,74,.12)', blue = '#0891b2', orange = '#d97706';

<?php if (!empty($cmdData)): ?>
new Chart(document.getElementById('chartCommandes'), {
  type: 'line',
  data: {
    labels: <?= json_encode($joursLabels) ?>,
    datasets: [{
      label: 'Commandes',
      data: <?= json_encode($joursCmd) ?>,
      borderColor: green,
      backgroundColor: greenLight,
      fill: true,
      tension: 0.4,
      pointRadius: 4,
      pointBackgroundColor: green,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
      x: { grid: { display: false } }
    }
  }
});
<?php endif; ?>

<?php if (!empty($usersParMois)): ?>
new Chart(document.getElementById('chartUsers'), {
  type: 'bar',
  data: {
    labels: <?= json_encode($moisLabels) ?>,
    datasets: [{
      label: 'Inscriptions',
      data: <?= json_encode($moisUsers) ?>,
      backgroundColor: 'rgba(8,145,178,.7)',
      borderRadius: 6,
    }]
  },
  options: {
    responsive: true,
    plugins: { legend: { display: false } },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: '#f1f5f9' } },
      x: { grid: { display: false } }
    }
  }
});
<?php endif; ?>

<?php if (!empty($cmdStatuts)): ?>
new Chart(document.getElementById('chartStatuts'), {
  type: 'doughnut',
  data: {
    labels: <?= json_encode(array_column($cmdStatuts, 'statut')) ?>,
    datasets: [{
      data: <?= json_encode(array_column($cmdStatuts, 'nb')) ?>,
      backgroundColor: ['#94a3b8','#d97706','#0891b2','#16a34a','#dc2626'],
      borderWidth: 0,
      hoverOffset: 4,
    }]
  },
  options: {
    responsive: true,
    cutout: '60%',
    plugins: { legend: { position: 'bottom', labels: { padding: 14, font: { size: 12 } } } }
  }
});
<?php endif; ?>
</script>

<?php require __DIR__ . '/layout_footer.php'; ?>
