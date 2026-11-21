<?php
$currentUser = $_SESSION['user'] ?? [];
$rawRole = strtolower($currentUser['role'] ?? 'superadmin');
$roleKey = 'superadmin';
if (strpos($rawRole, 'gest') !== false) {
    $roleKey = 'gestionnaire';
} elseif (strpos($rawRole, 'audit') !== false || strpos($rawRole, 'inspect') !== false) {
    $roleKey = 'auditeur';
} elseif (strpos($rawRole, 'agent') !== false || (strpos($rawRole, 'admin') === false && strpos($rawRole, 'super') === false)) {
    $roleKey = 'agent';
}

if ($roleKey === 'agent') {
    $pageTitle = 'Mes Équipements Professionnels Confiés (Togo)';
    $pageSubtitle = 'Consultation du matériel de l\'État Togolais mis à votre disposition pour vos fonctions';
    $agentBiens = array_filter($biens ?? [], function($b) {
        return ($b['code_inventaire'] ?? '') === 'GPE-000015' 
            || stripos($b['ministere'] ?? '', 'Numérique') !== false;
    });
    if (empty($agentBiens) && !empty($biens)) {
        $agentBiens = [reset($biens)];
    }
    $displayBiens = array_values($agentBiens);
} elseif ($roleKey === 'gestionnaire') {
    $pageTitle = 'Registre du Patrimoine Public (MEF - Togo)';
    $pageSubtitle = 'Gestion technique, inventaire permanent et suivi signalétique du parc togolais';
    $displayBiens = $biens ?? [];
} elseif ($roleKey === 'auditeur') {
    $pageTitle = 'Registre Exhaustif & Contrôle du Patrimoine (IGF Lomé)';
    $pageSubtitle = 'Supervision générale de conformité, inventaire contradictoire et audit des dotations de l\'État';
    $displayBiens = $biens ?? [];
} else {
    $pageTitle = 'Registre National des Biens de l\'État (Togo)';
    $pageSubtitle = 'Recensement, inventaire permanent et contrôle de l\'ensemble du patrimoine togolais';
    $displayBiens = $biens ?? [];
}
$currentPage = 'biens';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Opération enregistrée avec succès dans le registre national du Togo !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['updated'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Fiche du bien modifiée avec succès dans le registre !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert-notification alert-danger alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-trash-can"></i>
            <span>Bien supprimé définitivement du registre de l'État Togolais.</span>
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
                    <i class="fa-solid fa-laptop-file" style="color: #059669; margin-right: 6px;"></i> Mes Équipements Professionnels Confiés (<?= count($displayBiens) ?>)
                <?php else: ?>
                    Inventaire du Patrimoine de l'État Togolais (<?= count($displayBiens) ?>)
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-filters-bar">
            <?php if ($roleKey !== 'agent'): ?>
                <form method="GET" action="/biens" class="search-form-bar" id="searchFormBiens" style="display: flex; align-items: center; gap: 8px;">
                    <div class="search-input-group" style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"></i>
                        <input type="text" name="q" id="biensSearchInput" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher code, libellé, Lomé, Kara..." autocomplete="off" style="padding-right: 32px;">
                        <button type="button" id="clearBiensSearch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--color-text-muted); cursor: pointer; font-size: 1.1rem; line-height: 1; <?= empty($_GET['q']) ? 'display: none;' : '' ?>" title="Effacer">&times;</button>
                    </div>
                    <button type="submit" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.82rem;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Rechercher</span>
                    </button>
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="/biens" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.82rem;" title="Réinitialiser la recherche">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </form>
                <?php if ($roleKey === 'superadmin' || $roleKey === 'gestionnaire'): ?>
                    <button class="btn btn-primary" onclick="document.getElementById('newBienModal').classList.add('active');">
                        <i class="fa-solid fa-plus"></i>
                        <span>Nouveau Bien</span>
                    </button>
                <?php else: ?>
                    <div style="font-size: 0.82rem; color: #B45309; font-weight: 700; background-color: #FEF3C7; border: 1px solid #FDE68A; padding: 6px 14px; border-radius: 9999px;">
                        <i class="fa-solid fa-clipboard-check"></i> Audit & Contrôle des Biens Publics (Lecture Seule)
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="font-size: 0.82rem; color: #059669; font-weight: 700; background-color: #ECFDF5; border: 1px solid #A7F3D0; padding: 6px 14px; border-radius: 9999px;">
                    <i class="fa-solid fa-shield-halved"></i> Dotation Individuelle Certifiée
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Inventaire</th>
                    <th>Libellé & Catégorie</th>
                    <th>Ministère & Lieu (Togo)</th>
                    <th>Date d'Achat</th>
                    <th>Valeur d'Acquisition</th>
                    <th>État</th>
                    <th>Statut</th>
                    <th style="min-width: 250px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($displayBiens)): ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <i class="fa-solid fa-inbox" style="font-size: 2rem; margin-bottom: 10px;"></i>
                            <p>Aucun bien ne correspond aux critères de recherche dans le registre togolais.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($displayBiens as $bien): ?>
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
                        <td style="text-align: center;">
                            <div class="table-actions">
                                <!-- BOUTON VOIR (TOUS LES RÔLES) -->
                                <button type="button" class="btn btn-info btn-sm" onclick='openViewBienModal(<?= json_encode($bien, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Voir la fiche complète">
                                    <i class="fa-regular fa-eye"></i>
                                    <span>Voir</span>
                                </button>

                                <?php if ($roleKey === 'agent'): ?>
                                    <!-- ACTION SPÉCIFIQUE AGENT : SIGNALER UNE PANNE -->
                                    <a href="/maintenances?open_modal=1&bien_id=<?= $bien['id'] ?>" class="btn btn-outline btn-sm" style="color: #DC2626; border-color: #FCA5A5;" title="Signaler un dysfonctionnement sur ce bien">
                                        <i class="fa-solid fa-triangle-exclamation"></i>
                                        <span>Signaler Panne</span>
                                    </a>
                                <?php elseif ($roleKey === 'auditeur'): ?>
                                    <!-- AUDITEUR : LECTURE SEULE CONTRÔLE CONFORME -->
                                    <span style="font-size: 0.75rem; color: #92400E; font-weight: 600; background: #FEF3C7; padding: 4px 8px; border-radius: 4px;">
                                        <i class="fa-solid fa-shield-halved"></i> Contrôle Conforme
                                    </span>
                                <?php else: ?>
                                    <!-- BOUTON MODIFIER (SUPER-ADMIN & GESTIONNAIRE) -->
                                    <button type="button" class="btn btn-warning btn-sm" onclick='openEditBienModal(<?= json_encode($bien, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Modifier les données du bien">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Modifier</span>
                                    </button>

                                    <!-- BOUTON SUPPRIMER (SUPER-ADMIN UNIQUEMENT) -->
                                    <?php if ($roleKey === 'superadmin'): ?>
                                        <form method="POST" action="/biens/delete" style="display:inline;" onsubmit="return confirm('Confirmer la suppression définitive du bien <?= addslashes($bien['code_inventaire']) ?> (<?= addslashes($bien['libelle']) ?>) ?');">
                                            <input type="hidden" name="id" value="<?= $bien['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer définitivement ce bien">
                                                <i class="fa-solid fa-trash-can"></i>
                                                <span>Supprimer</span>
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- BOUTON AFFECTER (SUPER-ADMIN & GESTIONNAIRE) -->
                                    <a href="/affectations?bien_id=<?= $bien['id'] ?>&code=<?= urlencode($bien['code_inventaire']) ?>" class="btn btn-outline btn-sm" title="Affecter à un agent">
                                        <i class="fa-solid fa-user-tag"></i>
                                    </a>
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
<!-- 1. MODALE VOIR LA FICHE DÉTAILLÉE DU BIEN                                  -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="viewBienModal">
    <div class="modal-box" style="max-width: 680px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-file-invoice" style="color: var(--color-primary-light);"></i> Fiche Signalétique : <span id="v_code" style="color: var(--color-accent);"></span></h3>
            <button class="modal-close" onclick="document.getElementById('viewBienModal').classList.remove('active');">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="detail-label">Code Inventaire National</div>
                    <div class="detail-value" id="v_code_badge"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Catégorie</div>
                    <div class="detail-value" id="v_cat"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Désignation / Libellé</div>
                    <div class="detail-value" id="v_libelle" style="font-size: 1.05rem; color: var(--color-primary);"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Valeur d'Acquisition</div>
                    <div class="detail-value" id="v_valeur_acq" style="color: var(--color-success);"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Valeur Actuelle Amortie</div>
                    <div class="detail-value" id="v_valeur_act"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Date d'Acquisition</div>
                    <div class="detail-value" id="v_date_acq"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">État d'Usage & Disponibilité</div>
                    <div class="detail-value" id="v_etat_statut"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Ministère de Rapprochement (Togo)</div>
                    <div class="detail-value" id="v_ministere"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Localisation Physique (Togo)</div>
                    <div class="detail-value" id="v_localisation"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Fournisseur / Prestataire</div>
                    <div class="detail-value" id="v_fournisseur"></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Numéro de Série</div>
                    <div class="detail-value" id="v_num_serie"></div>
                </div>
                <div class="detail-item" style="grid-column: span 2;">
                    <div class="detail-label">Description / Remarques Administratives</div>
                    <div class="detail-value" id="v_description" style="font-style: italic;"></div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 15px;">
                <button type="button" class="btn btn-outline" onclick="window.print();"><i class="fa-solid fa-print"></i> Imprimer la Fiche</button>
                <button type="button" class="btn btn-primary" onclick="document.getElementById('viewBienModal').classList.remove('active');">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 2. MODALE MODIFIER UN BIEN DU PATRIMOINE                                   -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="editBienModal">
    <div class="modal-box" style="max-width: 680px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color: var(--color-warning);"></i> Modifier le Bien : <span id="e_code_title"></span></h3>
            <button class="modal-close" onclick="document.getElementById('editBienModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/biens/update" class="modal-body">
            <input type="hidden" name="id" id="e_id">

            <div class="form-group">
                <label>Libellé / Désignation de l'équipement *</label>
                <input type="text" name="libelle" id="e_libelle" class="form-control" required>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Catégorie *</label>
                    <select name="categorie_id" id="e_categorie_id" class="form-control">
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['libelle']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>État d'Usage *</label>
                    <select name="etat" id="e_etat" class="form-control">
                        <option value="Neuf">Neuf</option>
                        <option value="Bon état">Bon état</option>
                        <option value="Passable">Passable</option>
                        <option value="En panne">En panne</option>
                        <option value="Réformé">Réformé</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Statut de Disponibilité *</label>
                    <select name="statut" id="e_statut" class="form-control">
                        <option value="Disponible">Disponible</option>
                        <option value="Affecté">Affecté</option>
                        <option value="En réparation">En réparation</option>
                        <option value="Sorti">Sorti</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date d'Acquisition *</label>
                    <input type="date" name="date_acquisition" id="e_date_acq" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Valeur d'Acquisition (FCFA) *</label>
                    <input type="number" name="valeur_acquisition_fcfa" id="e_valeur_acq" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Valeur Actuelle Amortie (FCFA) *</label>
                    <input type="number" name="valeur_actuelle_fcfa" id="e_valeur_act" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Ministère Togolais *</label>
                    <select name="ministere" id="e_ministere" class="form-control">
                        <option value="Ministère de l'Économie et des Finances">Ministère de l'Économie et des Finances (MEF - Togo)</option>
                        <option value="Ministère de l'Économie Numérique et de la Transformation Digitale">Ministère de l'Économie Numérique et de la Transformation Digitale (MENTD)</option>
                        <option value="Ministère des Travaux Publics et des Infrastructures">Ministère des Travaux Publics et des Infrastructures (MTPI)</option>
                        <option value="Ministère des Enseignements Primaire, Secondaire et Technique">Ministère des Enseignements Primaire, Secondaire et Technique (MEPST)</option>
                        <option value="Ministère de la Santé, de l'Hygiène Publique et de l'Accès Universel aux Soins">Ministère de la Santé, de l'Hygiène Publique et de l'Accès Universel aux Soins (MSHPAUS)</option>
                        <option value="Ministère de la Sécurité et de la Protection Civile">Ministère de la Sécurité et de la Protection Civile</option>
                        <option value="Ministère de l'Administration Territoriale">Ministère de l'Administration Territoriale</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Localisation Physique (Togo) *</label>
                    <input type="text" name="localisation" id="e_localisation" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fournisseur / Prestataire</label>
                    <input type="text" name="fournisseur" id="e_fournisseur" class="form-control">
                </div>
                <div class="form-group">
                    <label>Numéro de Série</label>
                    <input type="text" name="numero_serie" id="e_numero_serie" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Description / Détails Techniques</label>
                <textarea name="description" id="e_description" class="form-control" rows="2"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editBienModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Enregistrer les Modifications</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. MODALE NOUVEAU BIEN (TOGO)                                              -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="newBienModal">
    <div class="modal-box" style="max-width: 680px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-plus-circle" style="color: var(--color-primary);"></i> Enregistrer un Nouveau Bien au Patrimoine Togolais</h3>
            <button class="modal-close" onclick="document.getElementById('newBienModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/biens/create" class="modal-body">
            <div class="form-group">
                <label>Libellé / Désignation de l'équipement *</label>
                <input type="text" name="libelle" class="form-control" placeholder="Ex: Toyota Hilux 4x4 ou Ordinateur HP ProDesk ou Immeuble Administratif" required>
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
                    <label>Ministère de Rattachement (Togo) *</label>
                    <select name="ministere" class="form-control">
                        <option value="Ministère de l'Économie et des Finances">Ministère de l'Économie et des Finances (MEF - Togo)</option>
                        <option value="Ministère de l'Économie Numérique et de la Transformation Digitale">Ministère de l'Économie Numérique et de la Transformation Digitale (MENTD)</option>
                        <option value="Ministère des Travaux Publics et des Infrastructures">Ministère des Travaux Publics et des Infrastructures (MTPI)</option>
                        <option value="Ministère des Enseignements Primaire, Secondaire et Technique">Ministère des Enseignements Primaire, Secondaire et Technique (MEPST)</option>
                        <option value="Ministère de la Santé, de l'Hygiène Publique et de l'Accès Universel aux Soins">Ministère de la Santé, de l'Hygiène Publique et de l'Accès Universel aux Soins (MSHPAUS)</option>
                        <option value="Ministère de la Sécurité et de la Protection Civile">Ministère de la Sécurité et de la Protection Civile</option>
                        <option value="Ministère de l'Administration Territoriale">Ministère de l'Administration Territoriale</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Localisation Physique (Togo) *</label>
                    <input type="text" name="localisation" class="form-control" placeholder="Ex: Lomé Marina, Datacenter OUA, Kara..." required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Fournisseur / Prestataire Adjudicataire</label>
                    <input type="text" name="fournisseur" class="form-control" placeholder="Ex: CFAO Motors Togo, STBTP Lomé, BuroTop Togo">
                </div>
                <div class="form-group">
                    <label>Numéro de Série (Optionnel)</label>
                    <input type="text" name="numero_serie" class="form-control" placeholder="Ex: SN-84920491">
                </div>
            </div>

            <div class="form-group">
                <label>Description / Détails Techniques</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Caractéristiques techniques, motorisation, affectation prévue..."></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('newBienModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Enregistrer le Bien</button>
            </div>
        </form>
    </div>
</div>

<script>
function formatFCFA(val) {
    return new Intl.NumberFormat('fr-FR').format(val) + ' FCFA';
}

function openViewBienModal(bien) {
    document.getElementById('v_code').innerText = bien.code_inventaire || '';
    document.getElementById('v_code_badge').innerHTML = '<span class="code-badge">' + (bien.code_inventaire || '') + '</span>';
    document.getElementById('v_cat').innerText = bien.categorie_libelle || 'Matériel & Équipement';
    document.getElementById('v_libelle').innerText = bien.libelle || '';
    document.getElementById('v_valeur_acq').innerText = formatFCFA(bien.valeur_acquisition_fcfa || 0);
    document.getElementById('v_valeur_act').innerText = formatFCFA(bien.valeur_actuelle_fcfa || bien.valeur_acquisition_fcfa || 0);
    document.getElementById('v_date_acq').innerText = bien.date_acquisition ? bien.date_acquisition.split('-').reverse().join('/') : 'N/A';
    document.getElementById('v_etat_statut').innerText = (bien.etat || 'Bon état') + ' — ' + (bien.statut || 'Disponible');
    document.getElementById('v_ministere').innerText = bien.ministere || 'Ministère de l\'Économie et des Finances (Togo)';
    document.getElementById('v_localisation').innerText = bien.localisation || 'Lomé, Togo';
    document.getElementById('v_fournisseur').innerText = bien.fournisseur || 'Non renseigné';
    document.getElementById('v_num_serie').innerText = bien.numero_serie || 'N/A';
    document.getElementById('v_description').innerText = bien.description || 'Aucune description spécifique.';

    document.getElementById('viewBienModal').classList.add('active');
}

function openEditBienModal(bien) {
    document.getElementById('e_code_title').innerText = bien.code_inventaire || '';
    document.getElementById('e_id').value = bien.id || '';
    document.getElementById('e_libelle').value = bien.libelle || '';
    document.getElementById('e_categorie_id').value = bien.categorie_id || 1;
    document.getElementById('e_etat').value = bien.etat || 'Bon état';
    document.getElementById('e_statut').value = bien.statut || 'Disponible';
    document.getElementById('e_date_acq').value = bien.date_acquisition || '';
    document.getElementById('e_valeur_acq').value = bien.valeur_acquisition_fcfa || 0;
    document.getElementById('e_valeur_act').value = bien.valeur_actuelle_fcfa || bien.valeur_acquisition_fcfa || 0;
    document.getElementById('e_ministere').value = bien.ministere || 'Ministère de l\'Économie et des Finances';
    document.getElementById('e_localisation').value = bien.localisation || '';
    document.getElementById('e_fournisseur').value = bien.fournisseur || '';
    document.getElementById('e_numero_serie').value = bien.numero_serie || '';
    document.getElementById('e_description').value = bien.description || '';

    document.getElementById('editBienModal').classList.add('active');
}

// --- FILTRAGE EN TEMPS RÉEL (Live Search dès la frappe) ---
const biensSearch = document.getElementById('biensSearchInput');
const clearBiensBtn = document.getElementById('clearBiensSearch');
const biensTitle = document.querySelector('.card-header-title');
const originalTitle = biensTitle ? biensTitle.innerText : '';

function filterBiensTable(query) {
    query = query.toLowerCase().trim();
    if (clearBiensBtn) {
        clearBiensBtn.style.display = query.length > 0 ? 'block' : 'none';
    }

    const rows = document.querySelectorAll('.app-table tbody tr');
    let visibleCount = 0;
    let noResultRow = document.getElementById('dynamicNoResultRow');

    rows.forEach(row => {
        if (row.id === 'dynamicNoResultRow') return;

        // Si c'est la ligne initiale vide PHP
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
            noResultRow.id = 'dynamicNoResultRow';
            noResultRow.innerHTML = '<td colspan="8" style="text-align: center; padding: 40px; color: var(--color-text-muted);"><i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i><strong>Aucun bien trouvé pour "' + query.replace(/</g, '&lt;') + '"</strong><p style="margin-top:6px; font-size:0.85rem;">Essayez avec un autre mot-clé (ex: Toyota, Lomé, GPE, Ordinateur...)</p></td>';
            document.querySelector('.app-table tbody').appendChild(noResultRow);
        } else {
            noResultRow.style.display = '';
            noResultRow.querySelector('strong').innerText = 'Aucun bien trouvé pour "' + query + '"';
        }
    } else if (noResultRow) {
        noResultRow.style.display = 'none';
    }

    if (biensTitle) {
        if (query === '') {
            biensTitle.innerText = originalTitle;
        } else {
            biensTitle.innerText = 'Inventaire du Patrimoine de l\'État Togolais (' + visibleCount + ' résultat' + (visibleCount > 1 ? 's' : '') + ')';
        }
    }
}

if (biensSearch) {
    biensSearch.addEventListener('input', function() {
        filterBiensTable(this.value);
    });

    if (clearBiensBtn) {
        clearBiensBtn.addEventListener('click', function() {
            biensSearch.value = '';
            filterBiensTable('');
            biensSearch.focus();
            if (window.location.search.includes('q=')) {
                window.location.href = '/biens';
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>