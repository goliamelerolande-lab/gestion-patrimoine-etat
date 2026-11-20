<?php
$pageTitle = 'Registre National des Biens de l\'État';
$pageSubtitle = 'Recensement, inventaire permanent et suivi signalétique du patrimoine public';
$currentPage = 'biens';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div style="background-color: var(--color-success-bg); color: var(--color-success); padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> Opération enregistrée avec succès dans le registre national !
    </div>
<?php endif; ?>

<div class="data-card">
    <div class="card-header-bar">
        <div>
            <h3 class="card-header-title">Inventaire des Biens de l'État (<?= count($biens) ?>)</h3>
        </div>
        <div class="card-filters-bar">
            <form method="GET" action="/biens" class="search-input-group">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="q" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher code, libellé, lieu...">
            </form>
            <button class="btn btn-primary" onclick="document.getElementById('newBienModal').classList.add('active');">
                <i class="fa-solid fa-plus"></i>
                <span>Nouveau Bien</span>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Inventaire</th>
                    <th>Libellé & Catégorie</th>
                    <th>Ministère & Lieu</th>
                    <th>Date d'Achat</th>
                    <th>Valeur d'Acquisition</th>
                    <th>État</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($biens)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Aucun bien ne correspond aux critères de recherche.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($biens as $bien): ?>
                    <tr>
                        <td>
                            <span class="code-badge"><?= htmlspecialchars($bien['code_inventaire']) ?></span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: var(--color-primary);"><?= htmlspecialchars($bien['libelle']) ?></div>
                            <small style="color: var(--color-text-muted);"><?= htmlspecialchars($bien['categorie_libelle'] ?? 'Équipement') ?></small>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; font-weight: 600;"><?= htmlspecialchars($bien['ministere']) ?></div>
                            <small style="color: var(--color-text-muted);"><i class="fa-solid fa-location-dot"></i> <?= htmlspecialchars($bien['localisation']) ?></small>
                        </td>
                        <td><?= date('d/m/Y', strtotime($bien['date_acquisition'])) ?></td>
                        <td class="price-tag"><?= number_format($bien['valeur_acquisition_fcfa'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <?php
                                $etatClass = 'status-neuf';
                                if ($bien['etat'] === 'Bon état') $etatClass = 'status-bon';
                                if ($bien['etat'] === 'En panne') $etatClass = 'status-panne';
                                if ($bien['etat'] === 'Passable') $etatClass = 'status-passable';
                                if ($bien['etat'] === 'Réformé') $etatClass = 'status-passable';
                            ?>
                            <span class="status-pill <?= $etatClass ?>"><?= htmlspecialchars($bien['etat']) ?></span>
                        </td>
                        <td>
                            <span style="font-size: 0.78rem; font-weight: 700; color: <?= $bien['statut'] === 'Disponible' ? 'var(--color-success)' : 'var(--color-info)' ?>;">
                                ● <?= htmlspecialchars($bien['statut']) ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-outline btn-sm" onclick="alert('Détails du bien : <?= addslashes($bien['code_inventaire']) ?> - <?= addslashes($bien['libelle']) ?>\nValeur : <?= number_format($bien['valeur_acquisition_fcfa'], 0, ',', ' ') ?> FCFA\nFournisseur : <?= addslashes($bien['fournisseur'] ?? 'N/A') ?>');" title="Fiche détaillée">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                            <a href="/affectations?bien_id=<?= $bien['id'] ?>&code=<?= urlencode($bien['code_inventaire']) ?>" class="btn btn-outline btn-sm" title="Affecter à un agent">
                                <i class="fa-solid fa-user-tag"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modale Nouveau Bien -->
<div class="modal-overlay" id="newBienModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Enregistrer un Nouveau Bien au Patrimoine</h3>
            <button class="modal-close" onclick="document.getElementById('newBienModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/biens/create" class="modal-body">
            <div class="form-group">
                <label>Libellé / Désignation de l'équipement *</label>
                <input type="text" name="libelle" class="form-control" placeholder="Ex: Toyota Hilux 4x4 ou Ordinateur HP ProDesk" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Catégorie *</label>
                    <select name="categorie_id" class="form-control">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>État d'Acquisition *</label>
                    <select name="etat" class="form-control">
                        <option value="Neuf">Neuf</option>
                        <option value="Bon état">Bon état</option>
                        <option value="Passable">Passable</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valeur d'Acquisition (FCFA) *</label>
                    <input type="number" name="valeur_acquisition_fcfa" class="form-control" placeholder="Ex: 15000000" required>
                </div>
                <div class="form-group">
                    <label>Date d'Acquisition *</label>
                    <input type="date" name="date_acquisition" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ministère de Rapprochement</label>
                    <select name="ministere" class="form-control">
                        <option value="Ministère de l'Économie et des Finances">Ministère de l'Économie et des Finances</option>
                        <option value="Ministère du Budget et du Portefeuille de l'État">Ministère du Budget et du Portefeuille de l'État</option>
                        <option value="Ministère de l'Éducation Nationale">Ministère de l'Éducation Nationale</option>
                        <option value="Ministère de la Santé">Ministère de la Santé</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Localisation Physique</label>
                    <input type="text" name="localisation" class="form-control" placeholder="Bâtiment, Bureau, Étage" required>
                </div>
            </div>

            <div class="form-group">
                <label>Fournisseur / Prestataire</label>
                <input type="text" name="fournisseur" class="form-control" placeholder="Nom de l'entreprise adjudicataire">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('newBienModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer le Bien</button>
            </div>
        </form>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
