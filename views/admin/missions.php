<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>🌍 Missions Humanitaires</h1><p><?= count($missions) ?> mission(s)</p></div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=mission_add" class="a-btn a-btn-primary">+ Nouvelle mission</a>
  </div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($missions)): ?>
    <div class="a-empty"><div class="ico">🌍</div><p>Aucune mission enregistrée.</p><a href="<?= BASE_URL ?>/index.php?page=admin&action=mission_add" class="a-btn a-btn-primary">Créer la première</a></div>
    <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Titre</th><th>Zone</th><th>Période</th><th>Statut</th><th>Bilan</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($missions as $m):
        $bs=['planifiee'=>'gray','en_cours'=>'info','terminee'=>'success','annulee'=>'danger'];
      ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $m['id'] ?></td>
        <td><strong><?= htmlspecialchars($m['titre']) ?></strong></td>
        <td><span class="a-badge a-badge-gray">📍 <?= htmlspecialchars($m['zone']) ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)">
          <?= date('d/m/Y',strtotime($m['date_debut'])) ?>
          <?php if ($m['date_fin']): ?> → <?= date('d/m/Y',strtotime($m['date_fin'])) ?><?php endif; ?>
        </td>
        <td><span class="badge badge-<?= $bs[$m['statut']]??'gray' ?>"><?= ucfirst($m['statut']) ?></span></td>
        <td style="font-size:12px;color:var(--text-muted);max-width:200px"><?= $m['bilan'] ? htmlspecialchars(mb_substr($m['bilan'],0,60)).'…' : '—' ?></td>
        <td>
          <div class="a-actions">
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=mission_edit&id=<?= $m['id'] ?>" class="a-btn a-btn-sm a-btn-outline">✏️</a>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=mission_delete&id=<?= $m['id'] ?>" class="a-btn a-btn-sm a-btn-danger" data-confirm="Supprimer cette mission ?">🗑️</a>
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
