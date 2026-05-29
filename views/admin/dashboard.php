<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>📊 Tableau de bord</h1>
    <p>Bienvenue <?= htmlspecialchars($_SESSION['user']['prenom']) ?> — Vue d'ensemble du système</p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacie_add" class="a-btn a-btn-primary">+ Ajouter une pharmacie</a>
  </div>
</div>

<!-- Stat Cards -->
<div class="a-stats">
  <div class="a-stat">
    <div class="stat-icon green">🏥</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['pharmacies'] ?></div>
      <div class="a-stat-lbl">Pharmacies</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon blue">💊</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['medicaments'] ?></div>
      <div class="a-stat-lbl">Médicaments</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon orange">🛒</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['commandes'] ?></div>
      <div class="a-stat-lbl">Commandes</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon teal">👥</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['utilisateurs'] ?></div>
      <div class="a-stat-lbl">Utilisateurs</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon purple">📝</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['articles'] ?></div>
      <div class="a-stat-lbl">Articles publiés</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon red">❤️</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= number_format($stats['dons'], 0, ',', ' ') ?> F</div>
      <div class="a-stat-lbl">Dons confirmés</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon green">🌍</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['missions'] ?></div>
      <div class="a-stat-lbl">Missions</div>
    </div>
  </div>
  <div class="a-stat">
    <div class="stat-icon orange">💬</div>
    <div class="stat-info">
      <div class="a-stat-val"><?= $stats['contacts'] ?></div>
      <div class="a-stat-lbl">Messages non lus</div>
    </div>
  </div>
</div>

<!-- Dashboard Grid -->
<div class="a-dash-grid">

  <!-- Commandes récentes -->
  <div class="a-card">
    <div class="a-card-head">
      <h2>🛒 Commandes récentes</h2>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=commandes" class="a-btn a-btn-sm a-btn-outline">Tout voir</a>
    </div>
    <div class="a-table-wrap">
      <?php if (empty($recentes)): ?>
      <div class="a-empty"><p>Aucune commande pour le moment.</p></div>
      <?php else: ?>
      <table>
        <thead>
          <tr>
            <th>#</th>
            <th>Client</th>
            <th>Total</th>
            <th>Statut</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($recentes as $c): ?>
        <tr>
          <td><strong>#<?= $c['id'] ?></strong></td>
          <td><?= $c['nom'] ? htmlspecialchars($c['prenom'].' '.$c['nom']) : '<span style="color:var(--text-muted)">Visiteur</span>' ?></td>
          <td><strong><?= number_format($c['total'], 0, ',', ' ') ?> F</strong></td>
          <td>
            <?php $badges = ['panier'=>'gray','en_attente'=>'warning','confirmee'=>'info','livree'=>'success','annulee'=>'danger']; ?>
            <span class="badge badge-<?= $badges[$c['statut']] ?? 'gray' ?>"><?= ucfirst($c['statut']) ?></span>
          </td>
          <td style="color:var(--text-muted);font-size:12px"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php endif; ?>
    </div>
  </div>

  <!-- Stocks faibles -->
  <div class="a-card">
    <div class="a-card-head">
      <h2>⚠️ Stocks faibles</h2>
      <a href="<?= BASE_URL ?>/index.php?page=admin&action=stocks" class="a-btn a-btn-sm a-btn-outline">Gérer</a>
    </div>
    <div class="a-card-body">
      <?php if (empty($stocksFaibles)): ?>
      <div class="a-empty"><p>✅ Tous les stocks sont suffisants.</p></div>
      <?php else: ?>
      <?php foreach ($stocksFaibles as $s): ?>
      <?php $pct = min(100, round($s['stock_global'] / 30 * 100)); ?>
      <div style="margin-bottom:16px">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px">
          <span style="font-size:13px;font-weight:500"><?= htmlspecialchars($s['nom']) ?></span>
          <span style="font-size:12px;color:<?= $s['stock_global'] < 10 ? 'var(--danger)' : 'var(--warning)' ?>;font-weight:600"><?= $s['stock_global'] ?> unités</span>
        </div>
        <div class="a-progress">
          <div class="progress-bar <?= $s['stock_global'] < 10 ? 'danger' : 'warning' ?>" style="width:<?= $pct ?>%"></div>
        </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- Commandes par statut -->
    <div class="a-card-head" style="border-top:1px solid var(--border)">
      <h2>📈 Commandes par statut</h2>
    </div>
    <div class="a-card-body">
      <?php
      $cmdMap = [];
      foreach ($cmdStatuts as $cs) $cmdMap[$cs['statut']] = $cs['nb'];
      $total_cmd = array_sum($cmdMap);
      $statutsAll = ['panier'=>'gray','en_attente'=>'warning','confirmee'=>'info','livree'=>'success','annulee'=>'danger'];
      foreach ($statutsAll as $s => $cls):
        $nb = $cmdMap[$s] ?? 0;
        $pct = $total_cmd > 0 ? round($nb/$total_cmd*100) : 0;
      ?>
      <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
        <span style="width:90px;font-size:12px;font-weight:600;color:var(--text-muted)"><?= ucfirst($s) ?></span>
        <div class="a-progress" style="flex:1">
          <div class="progress-bar <?= $cls === 'success' ? '' : ($cls === 'danger' ? 'danger' : ($cls === 'warning' ? 'warning' : '')) ?>" style="width:<?= $pct ?>%"></div>
        </div>
        <span style="font-size:12px;font-weight:700;width:30px;text-align:right"><?= $nb ?></span>
      </div>
      <?php endforeach; ?>
    </div>
  </div>

</div><!-- /.dash-grid -->

<?php require __DIR__ . '/layout_footer.php'; ?>
