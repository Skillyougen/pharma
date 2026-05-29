<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>🛒 Commandes</h1>
    <p><?= count($commandes) ?> commande(s)</p>
  </div>
</div>

<!-- Filtres statut -->
<div style="display:flex;gap:8px;margin-bottom:16px;flex-wrap:wrap">
  <?php $statuts=[''=>'Toutes','panier'=>'🛒 Panier','en_attente'=>'⏳ En attente','confirmee'=>'✅ Confirmées','livree'=>'📦 Livrées','annulee'=>'❌ Annulées']; ?>
  <?php foreach ($statuts as $s=>$l): ?>
  <a href="?page=admin&action=commandes<?= $s?'&statut='.$s:'' ?>"
     class="btn btn-sm <?= $statut===$s?'btn-primary':'btn-outline' ?>"><?= $l ?></a>
  <?php endforeach; ?>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($commandes)): ?>
    <div class="a-empty"><div class="ico">🛒</div><p>Aucune commande trouvée.</p></div>
    <?php else: ?>
    <table>
      <thead>
        <tr><th>#</th><th>Client</th><th>Total</th><th>Mode livraison</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
      <?php foreach ($commandes as $c):
        $badges=['panier'=>'gray','en_attente'=>'warning','confirmee'=>'info','livree'=>'success','annulee'=>'danger'];
      ?>
      <tr>
        <td><strong>#<?= $c['id'] ?></strong></td>
        <td>
          <?php if ($c['nom']): ?>
          <div style="font-weight:500"><?= htmlspecialchars($c['prenom'].' '.$c['nom']) ?></div>
          <div style="font-size:11px;color:var(--text-muted)"><?= htmlspecialchars($c['user_email'] ?? '') ?></div>
          <?php else: ?><span style="color:var(--text-muted);font-size:12px">Visiteur</span><?php endif; ?>
        </td>
        <td><strong><?= number_format($c['total'],0,',',' ') ?> F</strong></td>
        <td><span class="a-badge a-badge-gray"><?= $c['mode_livraison']==='livraison'?'🚚 Livraison':'🏪 Retrait' ?></span></td>
        <td><span class="badge badge-<?= $badges[$c['statut']]??'gray' ?>"><?= ucfirst($c['statut']) ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y H:i',strtotime($c['created_at'])) ?></td>
        <td>
          <a href="?page=admin&action=commande_detail&id=<?= $c['id'] ?>" class="a-btn a-btn-sm a-btn-outline">👁️ Détail</a>
        </td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
