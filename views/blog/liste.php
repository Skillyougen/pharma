<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="page-banner"><div class="page-banner-bg"></div><div class="page-banner-overlay"></div><h1 class="page-banner-title">Blog Santé</h1></div>
<div class="section">
<div class="section-header"><div><h2 class="section-title">Articles & Actualités</h2><div class="section-underline"></div></div></div>
<?php if(empty($articles)): ?><p style="color:var(--text-muted)">Aucun article publié.</p><?php else: ?>
<div class="grid-3"><?php foreach($articles as $a): ?>
<div class="card"><div class="blog-card-img">📰</div><div class="blog-card-body">
<?php if($a['tag']): ?><span class="badge-tag"><?= Security::h($a['tag']) ?></span><?php endif; ?>
<div class="blog-card-title"><?= Security::h($a['titre']) ?></div>
<?php if($a['extrait']): ?><p style="font-size:13px;color:var(--text-muted)"><?= Security::h(mb_substr($a['extrait'],0,120)) ?>…</p><?php endif; ?>
</div><div class="blog-card-footer"><span>📅 <?= date('d/m/Y',strtotime($a['created_at'])) ?></span><a href="<?= BASE_URL ?>/index.php?page=blog&action=detail&id=<?= $a['id'] ?>" class="blog-read">Lire →</a></div></div>
<?php endforeach; ?></div><?php endif; ?>
</div>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
