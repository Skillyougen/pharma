<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1><?= $medicament ? '✏️ Modifier le médicament' : '➕ Ajouter un médicament' ?></h1>
    <p><?= $medicament ? htmlspecialchars($medicament['nom']) : 'Nouveau médicament dans la base' ?></p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicaments" class="a-btn a-btn-outline">← Retour</a>
  </div>
</div>

<?php if ($error): ?><div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="a-card">
  <div class="a-card-head"><h2>💊 Informations du médicament</h2></div>
  <div class="a-card-body">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Nom commercial <span class="required">*</span></label>
          <input type="text" name="nom" class="a-form-ctrl" required
                 placeholder="ex: Paracétamol 500mg"
                 value="<?= htmlspecialchars($medicament['nom'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">DCI (Dénomination Commune)</label>
          <input type="text" name="dci" class="a-form-ctrl"
                 placeholder="ex: Paracétamol"
                 value="<?= htmlspecialchars($medicament['dci'] ?? '') ?>">
        </div>
      </div>

      <div class="a-form-grid-3">
        <div class="a-form-group">
          <label class="a-form-label">Catégorie <span class="required">*</span></label>
          <select name="categorie_id" class="a-form-select" required>
            <?php foreach ($categories as $c): ?>
            <option value="<?= $c['id'] ?>" <?= ($medicament['categorie_id'] ?? '') == $c['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($c['icone'].' '.$c['nom']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Forme</label>
          <select name="forme" class="a-form-select">
            <?php foreach (['comprime'=>'💊 Comprimé','gelule'=>'💊 Gélule','sirop'=>'🧴 Sirop','injectable'=>'💉 Injectable','pommade'=>'🫙 Pommade','autre'=>'📦 Autre'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= ($medicament['forme'] ?? 'comprime') === $v ? 'selected' : '' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Dosage</label>
          <input type="text" name="dosage" class="a-form-ctrl"
                 placeholder="ex: 500mg"
                 value="<?= htmlspecialchars($medicament['dosage'] ?? '') ?>">
        </div>
      </div>

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Prix (FCFA) <span class="required">*</span></label>
          <input type="number" name="prix" class="a-form-ctrl" required min="0" step="50"
                 value="<?= htmlspecialchars($medicament['prix'] ?? '0') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Stock global</label>
          <input type="number" name="stock_global" class="a-form-ctrl" min="0"
                 value="<?= htmlspecialchars($medicament['stock_global'] ?? '0') ?>">
        </div>
      </div>

      <div class="a-form-group">
        <label class="a-form-label">Description</label>
        <textarea name="description" class="a-form-textarea" rows="3" placeholder="Description, indications..."><?= htmlspecialchars($medicament['description'] ?? '') ?></textarea>
      </div>

      <div class="a-form-group">
        <label class="form-check">
          <input type="checkbox" name="ordonnance" value="1" <?= !empty($medicament['ordonnance']) ? 'checked' : '' ?>>
          <span>📋 Médicament sur ordonnance</span>
        </label>
      </div>

      <div style="display:flex;gap:8px;margin-top:8px">
        <button type="submit" class="a-btn a-btn-primary">
          <?= $medicament ? '💾 Enregistrer' : '➕ Ajouter le médicament' ?>
        </button>
        <a href="<?= BASE_URL ?>/index.php?page=admin&action=medicaments" class="a-btn a-btn-outline">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
