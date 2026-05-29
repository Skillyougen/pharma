<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1><?= $pharmacie ? '✏️ Modifier la pharmacie' : '➕ Ajouter une pharmacie' ?></h1>
    <p><?= $pharmacie ? htmlspecialchars($pharmacie['nom']) : 'Nouvelle pharmacie dans l\'annuaire' ?></p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacies" class="a-btn a-btn-outline">← Retour</a>
  </div>
</div>

<?php if ($error): ?>
<div class="a-alert a-alert-danger">⚠️ <?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<div class="a-card">
  <div class="a-card-head">
    <h2>🏥 Informations de la pharmacie</h2>
  </div>
  <div class="a-card-body">
    <form method="post" action="">
      <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Nom <span class="required">*</span></label>
          <input type="text" name="nom" class="a-form-ctrl" required
                 value="<?= htmlspecialchars($pharmacie['nom'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Zone <span class="required">*</span></label>
          <select name="zone_id" class="a-form-select" required>
            <?php foreach ($zones as $z): ?>
            <option value="<?= $z['id'] ?>" <?= ($pharmacie['zone_id'] ?? '') == $z['id'] ? 'selected' : '' ?>>
              <?= htmlspecialchars($z['nom']) ?>
            </option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="a-form-group">
        <label class="a-form-label">Adresse <span class="required">*</span></label>
        <input type="text" name="adresse" class="a-form-ctrl" required
               value="<?= htmlspecialchars($pharmacie['adresse'] ?? '') ?>">
      </div>

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Téléphone</label>
          <input type="text" name="telephone" class="a-form-ctrl"
                 value="<?= htmlspecialchars($pharmacie['telephone'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Email</label>
          <input type="email" name="email" class="a-form-ctrl"
                 value="<?= htmlspecialchars($pharmacie['email'] ?? '') ?>">
        </div>
      </div>

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Horaires</label>
          <input type="text" name="horaires" class="a-form-ctrl" placeholder="ex: Lun-Sam 7h-22h"
                 value="<?= htmlspecialchars($pharmacie['horaires'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Statut</label>
          <select name="statut" class="a-form-select">
            <option value="ouvert"  <?= ($pharmacie['statut'] ?? 'ouvert') === 'ouvert'  ? 'selected' : '' ?>>🟢 Ouvert</option>
            <option value="ferme"   <?= ($pharmacie['statut'] ?? '') === 'ferme'   ? 'selected' : '' ?>>🔴 Fermé</option>
            <option value="urgence" <?= ($pharmacie['statut'] ?? '') === 'urgence' ? 'selected' : '' ?>>🟡 Urgence (24h)</option>
          </select>
        </div>
      </div>

      <div class="a-form-grid-2">
        <div class="a-form-group">
          <label class="a-form-label">Latitude (GPS)</label>
          <input type="number" name="latitude" class="a-form-ctrl" step="any"
                 placeholder="ex: 4.0457957"
                 value="<?= htmlspecialchars($pharmacie['latitude'] ?? '') ?>">
        </div>
        <div class="a-form-group">
          <label class="a-form-label">Longitude (GPS)</label>
          <input type="number" name="longitude" class="a-form-ctrl" step="any"
                 placeholder="ex: 9.6952022"
                 value="<?= htmlspecialchars($pharmacie['longitude'] ?? '') ?>">
        </div>
      </div>

      <div style="display:flex;gap:8px;margin-top:8px">
        <button type="submit" class="a-btn a-btn-primary">
          <?= $pharmacie ? '💾 Enregistrer les modifications' : '➕ Ajouter la pharmacie' ?>
        </button>
        <a href="<?= BASE_URL ?>/index.php?page=admin&action=pharmacies" class="a-btn a-btn-outline">Annuler</a>
      </div>
    </form>
  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
