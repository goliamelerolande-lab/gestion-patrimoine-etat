<?php
$pageTitle = 'Traçabilité des Affectations & Mouvements';
$pageSubtitle = 'Suivi des fonctionnaires détenteurs, décharges de matériel et mutations administratives';
$currentPage = 'affectations';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div style="background-color: var(--color-success-bg); color: var(--color-success); padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> Affectation enregistrée avec succès dans le registre !
    </div>
<?php endif; ?>

<div class="data-card">
    <div class="card-header-bar">
        <h3 class="card-header-title">Registre des Affectations Actives (<?= count($affectations) ?>)</h3>
        <button class="btn btn-primary" onclick="document.getElementById('newAffectationModal').classList.add('active');">
            <i class="fa-solid fa-plus"></i>
            <span>Nouvelle Affectation</span>
        </button>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Inventaire</th>
                    <th>Agent / Bénéficiaire</th>
                    <th>Matricule & Service</th>
                    <th>Date d'Affectation</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($affectations)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <p>Aucune affectation active pour le moment.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($affectations as $aff): ?>
                    <tr>
                        <td><span class="code-badge"><?= htmlspecialchars($aff['code_inventaire']) ?></span></td>
                        <td>
                            <strong><i class="fa-solid fa-user"></i> <?= htmlspecialchars($aff['beneficiaire_nom']) ?></strong>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($aff['service_nom']) ?></div>
                            <small style="color: var(--color-text-muted);">Matricule : <?= htmlspecialchars($aff['beneficiaire_matricule']) ?></small>
                        </td>
                        <td><?= date('d/m/Y', strtotime($aff['date_affectation'])) ?></td>
                        <td><?= htmlspecialchars($aff['motif']) ?></td>
                        <td>
                            <span class="status-pill status-bon">● <?= htmlspecialchars($aff['statut']) ?></span>
                        </td>
                        <td>
                            <?php if ($aff['statut'] === 'En cours'): ?>
                                <form method="POST" action="/affectations/restituer" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $aff['id'] ?>">
                                    <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Confirmer la restitution du matériel par l\'agent ?');">
                                        <i class="fa-solid fa-rotate-left"></i> Restituer
                                    </button>
                                </form>
                            <?php else: ?>
                                <span style="font-size: 0.8rem; color: var(--color-text-muted);">Clôturé le <?= $aff['date_restitution'] ?? '' ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nouvelle Affectation -->
<div class="modal-overlay" id="newAffectationModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Attribuer un Bien à un Agent ou Service</h3>
            <button class="modal-close" onclick="document.getElementById('newAffectationModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/affectations/create" class="modal-body">
            <div class="form-group">
                <label>Sélectionner le Bien à Affecter *</label>
                <select name="bien_id" class="form-control" required id="bienSelect">
                    <?php foreach ($biens as $bien): ?>
                        <option value="<?= $bien['id'] ?>" data-code="<?= htmlspecialchars($bien['code_inventaire']) ?>">
                            <?= htmlspecialchars($bien['code_inventaire']) ?> — <?= htmlspecialchars($bien['libelle']) ?> (<?= htmlspecialchars($bien['statut']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="code_inventaire" id="codeInventaireHidden" value="<?= htmlspecialchars($biens[0]['code_inventaire'] ?? '') ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>Nom & Prénoms de l'Agent *</label>
                    <input type="text" name="beneficiaire_nom" class="form-control" placeholder="Ex: M. Amadou Koné" required>
                </div>
                <div class="form-group">
                    <label>Matricule Fonctionnaire *</label>
                    <input type="text" name="beneficiaire_matricule" class="form-control" placeholder="Ex: MAT-84920" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Direction ou Service de rattachement *</label>
                    <input type="text" name="service_nom" class="form-control" placeholder="Ex: Direction Générale du Trésor" required>
                </div>
                <div class="form-group">
                    <label>Date de Mise à Disposition</label>
                    <input type="date" name="date_affectation" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Motif de l'Affectation *</label>
                <input type="text" name="motif" class="form-control" placeholder="Ex: Véhicule de fonction ou dotation informatique de bureau" required>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('newAffectationModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Valider l'Affectation</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('bienSelect')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('codeInventaireHidden').value = selected.getAttribute('data-code');
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
