<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1><?= $article ? '✏️ Modifier l\'article' : '➕ Nouvel article' ?></h1>
    <p><?= $article ? htmlspecialchars($article['titre']) : 'Créer un article pour le blog' ?></p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=articles" class="a-btn a-btn-outline">← Retour</a>
  </div>
</div>

<?php if ($error): ?><div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px">
  <div class="a-card">
    <div class="a-card-head"><h2>📝 Contenu de l'article</h2></div>
    <div class="a-card-body">
      <form method="post" id="articleForm">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
        <div class="a-form-group">
          <label class="a-form-label">Titre <span class="required">*</span></label>
          <input type="text" name="titre" class="a-form-ctrl" required placeholder="Titre de l'article..."
                 value="<?= htmlspecialchars($article['titre'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Extrait</label>
          <textarea name="extrait" class="a-form-textarea" rows="2" placeholder="Court résumé affiché dans les listes..."><?= htmlspecialchars($article['extrait'] ?? '') ?></textarea>
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Contenu <span class="required">*</span></label>
          <textarea name="contenu" class="a-form-textarea" rows="12" placeholder="Contenu complet de l'article (HTML autorisé)..."><?= htmlspecialchars($article['contenu'] ?? '') ?></textarea>
        </div>
        <button type="submit" class="a-btn a-btn-primary"><?= $article ? '💾 Enregistrer' : '🚀 Publier' ?></button>
      </form>
    </div>
  </div>

  <!-- Sidebar options -->
  <div style="display:flex;flex-direction:column;gap:16px">
    <div class="a-card">
      <div class="a-card-head"><h2>⚙️ Options</h2></div>
      <div class="a-card-body">
        <div class="a-form-group">
          <label class="a-form-label">Statut</label>
          <select name="statut" form="articleForm" class="a-form-select">
            <option value="publie" <?= ($article['statut']??'publie')==='publie'?'selected':'' ?>>✅ Publié</option>
            <option value="brouillon" <?= ($article['statut']??'')==='brouillon'?'selected':'' ?>>📄 Brouillon</option>
          </select>
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Tag</label>
          <input type="text" name="tag" form="articleForm" class="a-form-ctrl" placeholder="ex: Prévention"
                 value="<?= htmlspecialchars($article['tag'] ?? '') ?>">
        </div>
      </div>
    </div>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
