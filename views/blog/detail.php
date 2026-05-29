<?php require BASE_PATH.'/views/layout/header.php'; ?>
<div class="page-banner"><div class="page-banner-bg"></div><div class="page-banner-overlay"></div><h1 class="page-banner-title"><?= Security::h(mb_substr($article['titre'],0,40)) ?></h1></div>
<div class="section" style="max-width:860px;margin:0 auto">
<a href="<?= BASE_URL ?>/index.php?page=blog" class="btn btn-sm btn-outline" style="margin-bottom:24px">← Blog</a>
<?php if($article['tag']): ?><span class="badge-tag"><?= Security::h($article['tag']) ?></span><br><br><?php endif; ?>
<h1 style="font-family:var(--font-serif);font-size:30px;margin-bottom:8px"><?= Security::h($article['titre']) ?></h1>
<p style="color:var(--text-muted);font-size:13px;margin-bottom:32px">📅 <?= date('d M Y',strtotime($article['created_at'])) ?></p>
<div style="background:var(--bg-light);border-radius:var(--radius-lg);padding:32px;border:1px solid var(--border);line-height:2;font-size:15px"><?= nl2br(Security::h($article['contenu'])) ?></div>
<div style="margin-top:24px"><a href="<?= BASE_URL ?>/index.php?page=blog" class="btn btn-outline">← Tous les articles</a></div>
</div>
<?php require BASE_PATH.'/views/layout/footer.php'; ?>
