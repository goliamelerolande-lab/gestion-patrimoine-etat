<?php
$currentUser = $_SESSION['user'] ?? [];
$rawRole = strtolower($currentUser['role'] ?? 'superadmin');
$roleKey = 'superadmin';
if (strpos($rawRole, 'gest') !== false) {
    $roleKey = 'gestionnaire';
} elseif (strpos($rawRole, 'agent') !== false || (strpos($rawRole, 'admin') === false && strpos($rawRole, 'super') === false)) {
    $roleKey = 'agent';
}

if ($roleKey === 'agent') {
    $pageTitle = 'Assistance & Signalement de Pannes (Togo)';
    $pageSubtitle = 'Déclaration rapide d\'incident sur vos matériels de fonction pour prise en charge';
    $agentMaints = array_filter($maintenances ?? [], function($m) {
        return ($m['code_inventaire'] ?? '') === 'GPE-000015' 
            || stripos($m['libelle_bien'] ?? '', 'Serveur') !== false;
    });
    $displayMaintenances = array_values($agentMaints);
} elseif ($roleKey === 'gestionnaire') {
    $pageTitle = 'Gestion de la Maintenance & Suivi des Pannes (Togo)';
    $pageSubtitle = 'Signalement d\'incidents, gestion des prestataires et validation des devis de réparation';
    $displayMaintenances = $maintenances ?? [];
} else {
    $pageTitle = 'Gestion de la Maintenance & Suivi des Pannes (Togo)';
    $pageSubtitle = 'Signalement d\'incidents, interventions techniques et maîtrise des coûts de réparation au Togo';
    $displayMaintenances = $maintenances ?? [];
}
$currentPage = 'maintenances';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Ticket de maintenance enregistré avec succès !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['updated'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Ticket de maintenance mis à jour avec succès !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert-notification alert-danger alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-trash-can"></i>
            <span>Ticket de maintenance supprimé avec succès.</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<div class="data-card">
    <div class="card-header-bar">
        <div>
            <h3 class="card-header-title">
                <?php if ($roleKey === 'agent'): ?>
                    <i class="fa-solid fa-screwdriver-wrench" style="color: #DC2626; margin-right: 6px;"></i> Mes Signalements d'Incidents Techniques (<?= count($displayMaintenances) ?>)
                <?php else: ?>
                    Tickets d'Intervention & Pannes du Patrimoine Public (<?= count($displayMaintenances) ?>)
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-filters-bar">
            <?php if ($roleKey !== 'agent'): ?>
                <form method="GET" action="/maintenances" class="search-form-bar" id="searchFormMaint" style="display: flex; align-items: center; gap: 8px;">
                    <div class="search-input-group" style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"></i>
                        <input type="text" name="q" id="maintSearchInput" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher panne, matériel, prestataire..." autocomplete="off" style="padding-right: 32px;">
                        <button type="button" id="clearMaintSearch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--color-text-muted); cursor: pointer; font-size: 1.1rem; line-height: 1; <?= empty($_GET['q']) ? 'display: none;' : '' ?>" title="Effacer">&times;</button>
                    </div>
                    <button type="submit" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.82rem;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Rechercher</span>
                    </button>
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="/maintenances" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.82rem;" title="Réinitialiser la recherche">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </form>
            <?php endif; ?>
            <button class="btn btn-primary" onclick="document.getElementById('newMaintenanceModal').classList.add('active');" style="<?= $roleKey === 'agent' ? 'background-color: #DC2626; border-color: #DC2626;' : '' ?>">
                <i class="fa-solid fa-triangle-exclamation"></i>
                <span><?= $roleKey === 'agent' ? 'Signaler une Panne sur mon Matériel' : 'Signaler une Panne' ?></span>
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Bien</th>
                    <th>Équipement & Panne</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <?php if ($roleKey !== 'agent'): ?>
                        <th>Prestataire Agréé (Togo)</th>
                        <th>Coût Réparation</th>
                    <?php endif; ?>
                    <th>Date Signalée</th>
                    <th style="min-width: <?= $roleKey === 'agent' ? '120px' : '250px' ?>; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($displayMaintenances)): ?>
                    <tr>
                        <td colspan="<?= $roleKey === 'agent' ? 6 : 8 ?>" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <i class="fa-solid fa-circle-check" style="font-size: 2rem; color: #059669; margin-bottom: 10px;"></i>
                            <p>Aucun incident ou panne déclaré pour le moment. Votre matériel est 100% opérationnel !</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($displayMaintenances as $m): ?>
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
                        <?php if ($roleKey !== 'agent'): ?>
                            <td><?= htmlspecialchars($m['prestataire'] ?? 'Régie Directe Lomé') ?></td>
                            <td class="price-tag"><?= number_format($m['cout_reparation_fcfa'] ?? 0, 0, ',', ' ') ?> FCFA</td>
                        <?php endif; ?>
                        <td><?= date('d/m/Y', strtotime($m['date_signalement'])) ?></td>
                        <td style="text-align: center;">
                            <div class="table-actions">
                                <!-- BOUTON VOIR (TOUS LES RÔLES) -->
                                <button type="button" class="btn btn-info btn-sm" onclick='openViewMaintenanceModal(<?= json_encode($m, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Voir le ticket">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Voir</span>
                                </button>

                                <?php if ($roleKey !== 'agent'): ?>
                                    <!-- BOUTON MODIFIER (SUPER-ADMIN & GESTIONNAIRE) -->
                                    <button type="button" class="btn btn-warning btn-sm" onclick='openEditMaintenanceModal(<?= json_encode($m, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Modifier le ticket / devis">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Modifier</span>
                                    </button>

                                    <!-- BOUTON SUPPRIMER (SUPER-ADMIN UNIQUEMENT) -->
                                    <?php if ($roleKey === 'superadmin'): ?>
                                        <form method="POST" action="/maintenances/delete" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de ce ticket pour <?= addslashes($m['code_inventaire']) ?> ?');">
                                            <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer ce ticket">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span>Supprimer</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 1. MODALE VOIR LE TICKET DE MAINTENANCE                                    -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="viewMaintenanceModal">
    <div class="modal-box" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-clipboard-check" style="color: var(--color-primary-light);"></i> Fiche Ticket Incident : <span id="vm_code" style="color: var(--color-accent);"></span></h3>
            <button class="modal-close" onclick="document.getElementById('viewMaintenanceModal').classList.remove('active');">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Code Inventaire</div>
                    <div class="detail-value" id="vm_code_badge"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Statut de Réparation</div>
                    <div class="detail-value" id="vm_statut"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Équipement Concerne</div>
                    <div class="detail-value" id="vm_libelle" style="font-size: 1.05rem; color: var(--color-primary);"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Degré d'Urgence / Priorité</div>
                    <div class="detail-value" id="vm_priorite"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Coût Validé des Travaux</div>
                    <div class="detail-value" id="vm_cout" style="color: var(--color-danger);"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date de Signalement</div>
                    <div class="detail-value" id="vm_date_sign"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date de Résolution Clôturée</div>
                    <div class="detail-value" id="vm_date_resol"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Prestataire Technique Agréé (Togo)</div>
                    <div class="detail-value" id="vm_prestataire"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Description Détaillée de la Défaillance</div>
                    <div class="detail-value" id="vm_description"></div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                <button type="button" class="btn btn-outline" onclick="window.print();"><i class="fa-solid fa-print"></i> Imprimer l'Ordre de Réparation</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('viewMaintenanceModal').classList.remove('active');">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. MODALE MODIFIER LE TICKET DE MAINTENANCE                                -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="editMaintenanceModal">
    <div class="modal-box" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color: var(--color-warning);"></i> Actualiser le Ticket : <span id="em_code_title"></span></h3>
            <button class="modal-close" onclick="document.getElementById('editMaintenanceModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/maintenances/update" class="modal-body">
            <input type="hidden" name="id" id="em_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Statut d'Intervention *</label>
                    <select name="statut" id="em_statut" class="form-control">
                        <option value="En attente">En attente de devis</option>
                        <option value="En cours">En cours de réparation</option>
                        <option value="Réparé">Réparé (Remis en service)</option>
                        <option value="Irréparable">Irréparable (Proposition réforme)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Niveau de Priorité *</label>
                    <select name="priorite" id="em_priorite" class="form-control">
                        <option value="Basse">Basse</option>
                        <option value="Moyenne">Moyenne</option>
                        <option value="Haute">Haute</option>
                        <option value="Critique">Critique (Blocage de service)</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Prestataire Technique Agréé (Togo) *</label>
                    <input type="text" name="prestataire" id="em_prestataire" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Montant Facture / Devis (FCFA) *</label>
                    <input type="number" name="cout_reparation_fcfa" id="em_cout" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Description des Travaux & Diagnostic *</label>
                <textarea name="description_panne" id="em_description" class="form-control" rows="3" required></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editMaintenanceModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Enregistrer les Modifications</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. MODALE NOUVELLE PANNE (TOGO)                                            -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="newMaintenanceModal">
    <div class="modal-box" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-triangle-exclamation" style="color: var(--color-danger);"></i> Signaler une Panne ou Défaillance Technique (Togo)</h3>
            <button class="modal-close" onclick="document.getElementById('newMaintenanceModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/maintenances/create" class="modal-body">
            <div class="form-group">
                <label>Sélectionner le Bien Défectueux *</label>
                <select name="bien_id" class="form-control" required id="bienMaintenanceSelect">
                    <?php 
                        $modalBiens = $biens ?? [];
                        if ($roleKey === 'agent') {
                            $modalBiens = array_filter($modalBiens, function($b) {
                                return ($b['code_inventaire'] ?? '') === 'GPE-000015' || stripos($b['ministere'] ?? '', 'Numérique') !== false;
                            });
                            if (empty($modalBiens) && !empty($biens)) $modalBiens = [reset($biens)];
                        }
                        $modalBiens = array_values($modalBiens);
                    ?>
                    <?php foreach ($modalBiens as $bien): ?>
                        <option value="<?= $bien['id'] ?>" data-code="<?= htmlspecialchars($bien['code_inventaire']) ?>" data-libelle="<?= htmlspecialchars($bien['libelle']) ?>">
                            <?= htmlspecialchars($bien['code_inventaire']) ?> — <?= htmlspecialchars($bien['libelle']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <input type="hidden" name="code_inventaire" id="codeMaintenanceHidden" value="<?= htmlspecialchars($modalBiens[0]['code_inventaire'] ?? 'GPE-000015') ?>">
            <input type="hidden" name="libelle_bien" id="libelleMaintenanceHidden" value="<?= htmlspecialchars($modalBiens[0]['libelle'] ?? 'Équipement') ?>">

            <div class="form-group">
                <label>Description du Dysfonctionnement / Problème Constaté *</label>
                <textarea name="description_panne" class="form-control" rows="3" placeholder="Précisez la panne (ex: écran noir, dysfonctionnement alimentation, alarme voyant rouge...)" required></textarea>
            </div>

            <div class="form-row">
                <div class="form-group" style="<?= $roleKey === 'agent' ? 'grid-column: span 2;' : '' ?>">
                    <label>Degré d'Urgence / Priorité *</label>
                    <select name="priorite" class="form-control">
                        <option value="Moyenne">Moyenne (Ralentissement de travail)</option>
                        <option value="Haute">Haute (Incident majeur)</option>
                        <option value="Critique">Critique (Blocage total du service)</option>
                        <option value="Basse">Basse (Simple anomalie)</option>
                    </select>
                </div>
                <?php if ($roleKey !== 'agent'): ?>
                    <div class="form-group">
                        <label>Coût Estimatif de Réparation (FCFA)</label>
                        <input type="number" name="cout_reparation_fcfa" class="form-control" placeholder="0">
                    </div>
                <?php else: ?>
                    <input type="hidden" name="cout_reparation_fcfa" value="0">
                <?php endif; ?>
            </div>

            <?php if ($roleKey !== 'agent'): ?>
                <div class="form-group">
                    <label>Prestataire Technique en Charge (Togo)</label>
                    <input type="text" name="prestataire" class="form-control" placeholder="Ex: CFAO Motors Togo, Lomé Maintenance IT, STBTP Lomé">
                </div>
            <?php else: ?>
                <input type="hidden" name="prestataire" value="Prise en charge interne MENTD">
                <div style="background-color: #EFF6FF; border: 1px solid #BFDBFE; color: #1E40AF; padding: 12px; border-radius: var(--radius-md); font-size: 0.82rem; margin-top: 10px;">
                    <i class="fa-solid fa-circle-info"></i> Votre demande sera immédiatement notifiée au Gestionnaire de Parc pour mandat d'un prestataire agréé de l'État.
                </div>
            <?php endif; ?>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px; padding-top: 14px; border-top: 1px solid var(--color-border);">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('newMaintenanceModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary" style="<?= $roleKey === 'agent' ? 'background-color: #DC2626; border-color: #DC2626;' : '' ?>">
                    <i class="fa-solid fa-paper-plane"></i> Enregistrer le Signalement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function updateSelectedMaintenanceBien() {
    const sel = document.getElementById('bienMaintenanceSelect');
    if (sel && sel.options[sel.selectedIndex]) {
        const opt = sel.options[sel.selectedIndex];
        const codeInput = document.getElementById('codeMaintenanceHidden');
        const libelleInput = document.getElementById('libelleMaintenanceHidden');
        if (codeInput) codeInput.value = opt.getAttribute('data-code') || '';
        if (libelleInput) libelleInput.value = opt.getAttribute('data-libelle') || '';
    }
}
document.getElementById('bienMaintenanceSelect')?.addEventListener('change', updateSelectedMaintenanceBien);
document.addEventListener('DOMContentLoaded', updateSelectedMaintenanceBien);

function formatFCFA(val) {
    return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
}

function openViewMaintenanceModal(m) {
    document.getElementById('vm_code').innerText = m.code_inventaire || '';
    document.getElementById('vm_code_badge').innerHTML = '<span class="code-badge">' + (m.code_inventaire || '') + '</span>';
    document.getElementById('vm_statut').innerText = m.statut || 'En attente';
    document.getElementById('vm_libelle').innerText = m.libelle_bien || 'Équipement';
    document.getElementById('vm_priorite').innerText = m.priorite || 'Moyenne';
    document.getElementById('vm_cout').innerText = formatFCFA(m.cout_reparation_fcfa || 0);
    document.getElementById('vm_date_sign').innerText = m.date_signalement ? m.date_signalement.split('-').reverse().join('/') : 'N/A';
    document.getElementById('vm_date_resol').innerText = m.date_resolution ? m.date_resolution.split('-').reverse().join('/') : 'Intervention en cours';
    document.getElementById('vm_prestataire').innerText = m.prestataire || 'Régie Directe Lomé';
    document.getElementById('vm_description').innerText = m.description_panne || 'Aucune description fournie.';

    document.getElementById('viewMaintenanceModal').classList.add('active');
}

function openEditMaintenanceModal(m) {
    document.getElementById('em_code_title').innerText = m.code_inventaire || '';
    document.getElementById('em_id').value = m.id || '';
    document.getElementById('em_statut').value = m.statut || 'En attente';
    document.getElementById('em_priorite').value = m.priorite || 'Moyenne';
    document.getElementById('em_prestataire').value = m.prestataire || '';
    document.getElementById('em_cout').value = m.cout_reparation_fcfa || 0;
    document.getElementById('em_description').value = m.description_panne || '';

    document.getElementById('editMaintenanceModal').classList.add('active');
}

// --- FILTRAGE EN TEMPS RÉEL (Live Search Maintenances) ---
const maintSearch = document.getElementById('maintSearchInput');
const clearMaintBtn = document.getElementById('clearMaintSearch');
const maintTitle = document.querySelector('.card-header-title');
const originalMaintTitle = maintTitle ? maintTitle.innerText : '';

function filterMaintTable(query) {
    query = query.toLowerCase().trim();
    if (clearMaintBtn) {
        clearMaintBtn.style.display = query.length > 0 ? 'block' : 'none';
    }

    const rows = document.querySelectorAll('.app-table tbody tr');
    let visibleCount = 0;
    let noResultRow = document.getElementById('dynamicNoResultMaintRow');

    rows.forEach(row => {
        if (row.id === 'dynamicNoResultMaintRow') return;

        if (row.cells.length === 1 && row.cells[0].colSpan > 1) {
            row.style.display = query === '' ? '' : 'none';
            return;
        }

        const text = row.innerText.toLowerCase();
        if (query === '' || text.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    if (visibleCount === 0 && query !== '') {
        if (!noResultRow) {
            noResultRow = document.createElement('tr');
            noResultRow.id = 'dynamicNoResultMaintRow';
            noResultRow.innerHTML = '<td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);"><i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i><strong>Aucun ticket trouvé pour "' + query.replace(/</g, '&lt;') + '"</strong></td>';
            document.querySelector('.app-table tbody').appendChild(noResultRow);
        } else {
            noResultRow.style.display = '';
            noResultRow.querySelector('strong').innerText = 'Aucun ticket trouvé pour "' + query + '"';
        }
    } else if (noResultRow) {
        noResultRow.style.display = 'none';
    }

    if (maintTitle) {
        if (query === '') {
            maintTitle.innerText = originalMaintTitle;
        } else {
            maintTitle.innerText = 'Tickets d\'Intervention & Pannes du Patrimoine Public (' + visibleCount + ' résultat' + (visibleCount > 1 ? 's' : '') + ')';
        }
    }
}

if (maintSearch) {
    maintSearch.addEventListener('input', function() {
        filterMaintTable(this.value);
    });

    if (clearMaintBtn) {
        clearMaintBtn.addEventListener('click', function() {
            maintSearch.value = '';
            filterMaintTable('');
            maintSearch.focus();
            if (window.location.search.includes('q=')) {
                window.location.href = '/maintenances';
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>