<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1"><h1><?= $mission ? '✏️ Modifier la mission' : '➕ Nouvelle mission' ?></h1></div>
  <div class="a-page-actions"><a href="<?= BASE_URL ?>/index.php?page=admin&action=missions" class="a-btn a-btn-outline">← Retour</a></div>
</div>

<?php if ($error): ?><div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($error) ?></div><?php endif; ?>

<div class="a-card">
  <div class="a-card-head"><h2>🌍 Informations de la mission</h2></div>
  <div class="a-card-body">
    <form method="post">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
      <div class="a-form-group">
        <label class="a-form-label">Titre <span class="required">*</span></label>
        <input type="text" name="titre" class="a-form-ctrl" required placeholder="ex: Campagne de vaccination Bonabéri"
               value="<?= htmlspecialchars($mission['titre'] ?? '') ?>">
      </div>
      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Zone / Localité</label>
          <input type="text" name="zone" class="a-form-ctrl" placeholder="ex: Bonabéri, Douala"
                 value="<?= htmlspecialchars($mission['zone'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Statut</label>
          <select name="statut" class="a-form-select">
            <?php foreach (['planifiee'=>'Planifiée','en_cours'=>'En cours','terminee'=>'Terminée','annulee'=>'Annulée'] as $v=>$l): ?>
            <option value="<?= $v ?>" <?= ($mission['statut']??'planifiee')===$v?'selected':'' ?>><?= $l ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Date de début</label>
          <input type="date" name="date_debut" class="a-form-ctrl"
                 value="<?= htmlspecialchars($mission['date_debut'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Date de fin</label>
          <input type="date" name="date_fin" class="a-form-ctrl"
                 value="<?= htmlspecialchars($mission['date_fin'] ?? '') ?>">
        </div>
      </div>
      <div class="a-form-group">
        <label class="a-form-label">Bilan / Résultats</label>
        <textarea name="bilan" class="a-form-textarea" rows="4" placeholder="Décrivez le bilan et les résultats de la mission..."><?= htmlspecialchars($mission['bilan'] ?? '') ?></textarea>
      </div>
      <div style="display:flex;gap:8px">
        <button type="submit" class="a-btn a-btn-primary"><?= $mission ? '💾 Enregistrer' : '➕ Créer la mission' ?></button>
        <a href="<?= BASE_URL ?>/index.php?page=admin&action=missions" class="a-btn a-btn-outline">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
