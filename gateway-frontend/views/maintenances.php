<?php
$pageTitle = 'Gestion de la Maintenance & Suivi des Pannes';
$pageSubtitle = 'Signalement d\'incidents, interventions techniques et maîtrise des coûts de réparation';
$currentPage = 'maintenances';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div style="background-color: var(--color-success-bg); color: var(--color-success); padding: 14px 20px; border-radius: var(--radius-md); margin-bottom: 20px; font-weight: 600;">
        <i class="fa-solid fa-circle-check"></i> Ticket de maintenance enregistré avec succès !
    </div>
<?php endif; ?>

<div class="data-card">
    <div class="card-header-bar">
        <h3 class="card-header-title">Tickets d'Intervention & Pannes en cours (<?= count($maintenances) ?>)</h3>
        <button class="btn btn-primary" onclick="document.getElementById('newMaintenanceModal').classList.add('active');">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Signaler une Panne</span>
        </button>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Bien</th>
                    <th>Équipement & Panne</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Prestataire Assuré</th>
                    <th>Coût Réparation</th>
                    <th>Date Signalée</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($maintenances)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <p>Aucun incident ou panne en cours d'intervention.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($maintenances as $m): ?>
                    <tr>
                        <td><span class="code-badge"><?= htmlspecialchars($m['code_inventaire']) ?></span></td>
                        <td>
                            <strong><?= htmlspecialchars($m['libelle_bien']) ?></strong>
                            <div style="font-size: 0.82rem; color: var(--color-danger); margin-top: 3px;">
                                <i class="fa-solid fa-wrench"></i> <?= htmlspecialchars($m['description_panne']) ?>
                            </div>
                        </td>
                        <td>
                            <?php
                                $prioColor = 'var(--color-warning)';
                                if ($m['priorite'] === 'Haute' || $m['priorite'] === 'Critique') $prioColor = 'var(--color-danger)';
                            ?>
                            <span style="font-size: 0.82rem; font-weight: 700; color: <?= $prioColor ?>;">
                                <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($m['priorite']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="status-pill status-panne">● <?= htmlspecialchars($m['statut']) ?></span>
                        </td>
                        <td><?= htmlspecialchars($m['prestataire'] ?? 'Régie Directe') ?></td>
                        <td class="price-tag"><?= number_format($m['cout_reparation_fcfa'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                        <td><?= date('d/m/Y', strtotime($m['date_signalement'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Nouvelle Panne -->
<div class="modal-overlay" id="newMaintenanceModal">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Signaler une Panne ou Défaillance Technique</h3>
            <button class="modal-close" onclick="document.getElementById('newMaintenanceModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/maintenances/create" class="modal-body">
            <div class="form-group">
                <label>Sélectionner le Bien Défectueux *</label>
                <select name="bien_id" class="form-control" required id="bienMaintenanceSelect">
                    <?php foreach ($biens as $bien): ?>
                        <option value="<?= $bien['id'] ?>" data-code="<?= htmlspecialchars($bien['code_inventaire']) ?>" data-libelle="<?= htmlspecialchars($bien['libelle']) ?>">
                            <?= htmlspecialchars($bien['code_inventaire']) ?> — <?= htmlspecialchars($bien['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="code_inventaire" id="codeMaintenanceHidden" value="<?= htmlspecialchars($biens[0]['code_inventaire'] ?? '') ?>">
            <input type="hidden" name="libelle_bien" id="libelleMaintenanceHidden" value="<?= htmlspecialchars($biens[0]['libelle'] ?? '') ?>">

            <div class="form-group">
                <label>Description du Dysfonctionnement / Problème *</label>
                <textarea name="description_panne" class="form-control" rows="3" placeholder="Précisez la panne (ex: Fuite d'huile, écran noir, tambour d'impression HS...)" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Degré d'Urgence / Priorité *</label>
                    <select name="priorite" class="form-control">
                        <option value="Moyenne">Moyenne</option>
                        <option value="Haute">Haute</option>
                        <option value="Critique">Critique (Blocage de service)</option>
                        <option value="Basse">Basse</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Coût Estimatif de Réparation (FCFA)</label>
                    <input type="number" name="cout_reparation_fcfa" class="form-control" placeholder="0">
                </div>
            </div>

            <div class="form-group">
                <label>Prestataire Technique en Charge</label>
                <input type="text" name="prestataire" class="form-control" placeholder="Nom du garage agréé ou société de maintenance">
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('newMaintenanceModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-paper-plane"></i> Enregistrer le Signalement</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('bienMaintenanceSelect')?.addEventListener('change', function() {
    const selected = this.options[this.selectedIndex];
    document.getElementById('codeMaintenanceHidden').value = selected.getAttribute('data-code');
    document.getElementById('libelleMaintenanceHidden').value = selected.getAttribute('data-libelle');
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
