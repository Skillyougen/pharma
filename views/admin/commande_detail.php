<?php require __DIR__ . '/layout_header.php'; ?>

<div class="a-page-head">
  <div style="flex:1">
    <h1>🛒 Commande #<?= $commande['id'] ?></h1>
    <p>Détail et gestion du statut</p>
  </div>
  <div class="a-page-actions">
    <a href="<?= BASE_URL ?>/index.php?page=admin&action=commandes" class="a-btn a-btn-outline">← Retour</a>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 360px;gap:20px">

  <!-- Lignes de commande -->
  <div>
    <div class="a-card">
      <div class="a-card-head"><h2>💊 Médicaments commandés</h2></div>
      <div class="a-table-wrap">
        <?php if (empty($lignes)): ?>
        <div class="a-empty" style="padding:30px"><p>Aucun médicament dans cette commande.</p></div>
        <?php else: ?>
        <table>
          <thead><tr><th>Médicament</th><th>Qté</th><th>Prix unitaire</th><th>Sous-total</th></tr></thead>
          <tbody>
          <?php foreach ($lignes as $l): ?>
          <tr>
            <td><strong><?= htmlspecialchars($l['med_nom']) ?></strong></td>
            <td><?= $l['quantite'] ?></td>
            <td><?= number_format($l['prix_unitaire'],0,',',' ') ?> F</td>
            <td><strong><?= number_format($l['quantite']*$l['prix_unitaire'],0,',',' ') ?> F</strong></td>
          </tr>
          <?php endforeach; ?>
          </tbody>
        </table>
        <?php endif; ?>
      </div>
      <div style="padding:16px;text-align:right;border-top:1px solid var(--border)">
        <span style="font-size:18px;font-weight:800">Total : <?= number_format($commande['total'],0,',',' ') ?> FCFA</span>
      </div>
    </div>
  </div>

  <!-- Sidebar info -->
  <div style="display:flex;flex-direction:column;gap:16px">

    <!-- Statut -->
    <div class="a-card">
      <div class="a-card-head"><h2>📋 Statut de la commande</h2></div>
      <div class="a-card-body">
        <?php
        $badges=['panier'=>'gray','en_attente'=>'warning','confirmee'=>'info','livree'=>'success','annulee'=>'danger'];
        ?>
        <div style="margin-bottom:16px;text-align:center">
          <span class="badge badge-<?= $badges[$commande['statut']]??'gray' ?>" style="font-size:14px;padding:6px 16px">
            <?= ucfirst($commande['statut']) ?>
          </span>
        </div>
        <form method="post">
          <input type="hidden" name="csrf_token" value="<?= Security::csrf() ?>">
          <div class="a-form-group">
            <label class="a-form-label">Changer le statut</label>
            <select name="statut" class="a-form-select">
              <?php foreach (['panier','en_attente','confirmee','livree','annulee'] as $s): ?>
              <option value="<?= $s ?>" <?= $commande['statut']===$s?'selected':'' ?>><?= ucfirst($s) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <button type="submit" class="a-btn a-btn-primary" style="width:100%">💾 Mettre à jour</button>
        </form>
      </div>
    </div>

    <!-- Client -->
    <div class="a-card">
      <div class="a-card-head"><h2>👤 Client</h2></div>
      <div class="a-card-body" style="font-size:13px">
        <?php if ($commande['nom']): ?>
        <p><strong><?= htmlspecialchars($commande['prenom'].' '.$commande['nom']) ?></strong></p>
        <?php if ($commande['user_email']): ?><p style="color:var(--text-muted)">📧 <?= htmlspecialchars($commande['user_email']) ?></p><?php endif; ?>
        <?php if ($commande['telephone']): ?><p style="color:var(--text-muted)">📞 <?= htmlspecialchars($commande['telephone']) ?></p><?php endif; ?>
        <?php else: ?><p style="color:var(--text-muted)">Commande de visiteur (non connecté)</p><?php endif; ?>
      </div>
    </div>

    <!-- Livraison -->
    <div class="a-card">
      <div class="a-card-head"><h2>🚚 Livraison</h2></div>
      <div class="a-card-body" style="font-size:13px">
        <p><strong><?= $commande['mode_livraison']==='livraison'?'🚚 Livraison à domicile':'🏪 Retrait en pharmacie' ?></strong></p>
        <?php if ($commande['adresse_livraison']): ?>
        <p style="color:var(--text-muted);margin-top:8px">📍 <?= htmlspecialchars($commande['adresse_livraison']) ?></p>
        <?php endif; ?>
        <?php if ($commande['note']): ?>
        <p style="color:var(--text-muted);margin-top:8px;font-style:italic">"<?= htmlspecialchars($commande['note']) ?>"</p>
        <?php endif; ?>
        <p style="color:var(--text-muted);margin-top:12px;font-size:11px">📅 <?= date('d/m/Y à H:i', strtotime($commande['created_at'])) ?></p>
      </div>
    </div>

  </div>
</div>

<?php require __DIR__ . '/layout_footer.php'; ?>
