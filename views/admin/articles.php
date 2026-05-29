<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>📝 Articles du Blog</h1><p><?= count($articles) ?> article(s)</p></div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=article_add" class="a-btn a-btn-primary">+ Nouvel article</a>
  </div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($articles)): ?>
    <div class="a-empty"><div class="ico">📝</div><p>Aucun article.</p><a href="<?= BASE_URL ?>/index.php?page=admin&action=article_add" class="a-btn a-btn-primary">Créer le premier</a></div>
    <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Titre</th><th>Tag</th><th>Statut</th><th>Auteur</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($articles as $a): ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $a['id'] ?></td>
        <td><strong><?= htmlspecialchars($a['titre']) ?></strong><?php if ($a['extrait']): ?><div style="font-size:11px;color:var(--text-muted);margin-top:2px"><?= htmlspecialchars(mb_substr($a['extrait'],0,60)) ?>…</div><?php endif; ?></td>
        <td><?php if ($a['tag']): ?><span class="a-badge a-badge-navy"><?= htmlspecialchars($a['tag']) ?></span><?php else: ?>—<?php endif; ?></td>
        <td><span class="badge badge-<?= $a['statut']==='publie'?'success':'gray' ?>"><?= $a['statut']==='publie'?'✅ Publié':'📄 Brouillon' ?></span></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= $a['nom'] ? htmlspecialchars($a['prenom'].' '.$a['nom']) : '—' ?></td>
        <td style="font-size:12px;color:var(--text-muted)"><?= date('d/m/Y',strtotime($a['created_at'])) ?></td>
        <td><div class="a-actions">
          <a href="<?= BASE_URL ?>/index.php?page=admin&action=article_edit&id=<?= $a['id'] ?>" class="a-btn a-btn-sm a-btn-outline">✏️</a>
          <a href="<?= BASE_URL ?>/index.php?page=admin&action=article_delete&id=<?= $a['id'] ?>" class="a-btn a-btn-sm a-btn-danger" data-confirm="Supprimer cet article ?">🗑️</a>
        </div></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    <?php endif; ?>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
