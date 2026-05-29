<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>📧 Newsletter</h1><p><?= count($abonnes) ?> abonné(s)</p></div>
  <div class="a-page-actions">
    <?php if (!empty($abonnes)): ?>
    <a href="mailto:<?= implode(',', array_column($abonnes, 'email')) ?>" class="a-btn a-btn-primary">✉️ Envoyer un email groupé</a>
    <?php endif; ?>
  </div>
</div>

<!-- Stats -->
<div class="a-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
  <?php
  $pdo = getPDO();
  $actifs   = (int)$pdo->query("SELECT COUNT(*) FROM newsletter WHERE actif=1")->fetchColumn();
  $inactifs = count($abonnes) - $actifs;
  $cemois   = (int)$pdo->query("SELECT COUNT(*) FROM newsletter WHERE created_at >= DATE_FORMAT(NOW(),'%Y-%m-01')")->fetchColumn();
  ?>
  <div class="a-stat"><div class="stat-icon green">✅</div><div class="stat-info"><div class="a-stat-val"><?= $actifs ?></div><div class="a-stat-lbl">Abonnés actifs</div></div></div>
  <div class="a-stat"><div class="stat-icon orange">❌</div><div class="stat-info"><div class="a-stat-val"><?= $inactifs ?></div><div class="a-stat-lbl">Désabonnés</div></div></div>
  <div class="a-stat"><div class="stat-icon blue">📅</div><div class="stat-info"><div class="a-stat-val"><?= $cemois ?></div><div class="a-stat-lbl">Ce mois</div></div></div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($abonnes)): ?>
    <div class="a-empty"><div class="ico">📧</div><p>Aucun abonné à la newsletter.</p></div>
    <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Email</th><th>Statut</th><th>Inscrit le</th></tr></thead>
      <tbody>
      <?php foreach ($abonnes as $a): ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $a['id'] ?></td>
        <td><strong><?= htmlspecialchars($a['email']) ?></strong></td>
        <td>
          <?php if ($a['actif']): ?>
          <span class="a-badge a-badge-success">✅ Actif</span>
          <?php else: ?>
          <span class="a-badge a-badge-gray">❌ Désabonné</span>
          <?php endif; ?>
        </td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y', strtotime($a['created_at'])) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
