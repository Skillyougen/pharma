<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>❤️ Gestion des Dons</h1><p><?= count($dons) ?> don(s) enregistré(s)</p></div>
</div>

<!-- KPI -->
<div class="a-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
  <?php
  $pdo = getPDO();
  $confirmes = (int)$pdo->query("SELECT COUNT(*) FROM dons WHERE statut='confirme'")->fetchColumn();
  $attente   = (int)$pdo->query("SELECT COUNT(*) FROM dons WHERE statut='en_attente'")->fetchColumn();
  ?>
  <div class="a-stat"><div class="stat-icon green">💰</div><div class="stat-info"><div class="a-stat-val"><?= number_format($total,0,',',' ') ?> F</div><div class="a-stat-lbl">Total confirmé (FCFA)</div></div></div>
  <div class="a-stat"><div class="stat-icon blue">✅</div><div class="stat-info"><div class="a-stat-val"><?= $confirmes ?></div><div class="a-stat-lbl">Dons confirmés</div></div></div>
  <div class="a-stat"><div class="stat-icon orange">⏳</div><div class="stat-info"><div class="a-stat-val"><?= $attente ?></div><div class="a-stat-lbl">En attente</div></div></div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($dons)): ?>
    <div class="a-empty"><div class="ico">❤️</div><p>Aucun don enregistré.</p></div>
    <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Donateur</th><th>Montant</th><th>Méthode</th><th>Statut</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($dons as $d):
        $b=['confirme'=>'success','en_attente'=>'warning','echoue'=>'danger'];
      ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $d['id'] ?></td>
        <td>
          <div style="font-weight:500"><?= htmlspecialchars($d['nom_donateur'] ?? 'Anonyme') ?></div>
          <?php if ($d['email_donateur'] ?? ''): ?><div style="font-size:11px;color:var(--text-muted)"><?= htmlspecialchars($d['email_donateur']) ?></div><?php endif; ?>
        </td>
        <td><strong style="color:var(--primary)"><?= number_format($d['montant'],0,',',' ') ?> F</strong></td>
        <td>
          <?php $methods=['mtn_momo'=>'📱 MTN MoMo','orange_money'=>'🔶 Orange Money','carte'=>'💳 Carte']; ?>
          <span class="a-badge a-badge-gray"><?= $methods[$d['methode_paiement']] ?? $d['methode_paiement'] ?></span>
        </td>
        <td><span class="badge badge-<?= $b[$d['statut']]??'gray' ?>"><?= ucfirst(str_replace('_',' ',$d['statut'])) ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y H:i',strtotime($d['created_at'])) ?></td>
        <td>
          <div class="a-actions">
            <?php if ($d['statut'] !== 'confirme'): ?>
            <a href="?page=admin&action=don_statut&id=<?= $d['id'] ?>&statut=confirme" class="a-btn a-btn-sm a-btn-outline" title="Confirmer" data-confirm="Confirmer ce don ?">✅</a>
            <?php endif; ?>
            <?php if ($d['statut'] !== 'echoue'): ?>
            <a href="?page=admin&action=don_statut&id=<?= $d['id'] ?>&statut=echoue" class="a-btn a-btn-sm a-btn-danger" title="Marquer échoué" data-confirm="Marquer ce don comme échoué ?">❌</a>
            <?php endif; ?>
          </div>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
