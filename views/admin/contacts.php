<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1>💬 Messages de contact</h1><p><?= count($contacts) ?> message(s)</p></div>
</div>

<div class="a-card">
  <div class="a-table-wrap">
    <?php if (empty($contacts)): ?>
    <div class="a-empty"><div class="ico">💬</div><p>Aucun message reçu.</p></div>
    <?php else: ?>
    <table>
      <thead><tr><th>#</th><th>Expéditeur</th><th>Sujet</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>
      <tbody>
      <?php foreach ($contacts as $c): ?>
      <tr>
        <td style="color:var(--text-muted)"><?= $c['id'] ?></td>
        <td>
          <div style="font-weight:600"><?= htmlspecialchars($c['nom']) ?></div>
          <div style="font-size:11px;color:var(--text-muted)">📧 <?= htmlspecialchars($c['email']) ?></div>
          <?php if ($c['telephone'] ?? ''): ?><div style="font-size:11px;color:var(--text-muted)">📞 <?= htmlspecialchars($c['telephone']) ?></div><?php endif; ?>
        </td>
        <td><strong><?= htmlspecialchars($c['sujet'] ?? '—') ?></strong></td>
        <td style="max-width:300px;color:var(--text-muted);font-size:13px"><?= htmlspecialchars(mb_substr($c['message'],0,100)) ?>…</td>
        <td style="font-size:12px;color:var(--text-muted);white-space:nowrap"><?= date('d/m/Y H:i',strtotime($c['created_at'])) ?></td>
        <td>
          <div class="a-actions">
            <a href="mailto:<?= htmlspecialchars($c['email']) ?>?subject=Re: <?= htmlspecialchars($c['sujet']??'') ?>" class="a-btn a-btn-sm a-btn-outline" title="Répondre par email">✉️</a>
            <a href="<?= BASE_URL ?>/index.php?page=admin&action=contact_delete&id=<?= $c['id'] ?>" class="a-btn a-btn-sm a-btn-danger" data-confirm="Supprimer ce message ?">🗑️</a>
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
