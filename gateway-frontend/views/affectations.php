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
    $pageTitle = 'Mes Décharges & Prises en Charge de Matériel (Togo)';
    $pageSubtitle = 'Récépissés officiels de remise et suivi de vos dotations en matériel public';
    $agentAffs = array_filter($affectations ?? [], function($a) use ($currentUser) {
        return stripos($a['beneficiaire_nom'] ?? '', 'Mensah') !== false 
            || ($a['beneficiaire_matricule'] ?? '') === ($currentUser['matricule'] ?? 'MAT-TG-003')
            || ($a['code_inventaire'] ?? '') === 'GPE-000015';
    });
    if (empty($agentAffs) && !empty($affectations)) {
        $agentAffs = [reset($affectations)];
    }
    $displayAffectations = array_values($agentAffs);
} elseif ($roleKey === 'gestionnaire') {
    $pageTitle = 'Gestion des Affectations & Décharges de Matériel (Togo)';
    $pageSubtitle = 'Traçabilité des mouvements administratifs, dotations et décharges au Togo';
    $displayAffectations = $affectations ?? [];
} elseif ($roleKey === 'auditeur') {
    $pageTitle = 'Registre des Affectations & Décharges (IGF Lomé)';
    $pageSubtitle = 'Traçabilité des mouvements administratifs, décharges certifiées et audit des détenteurs';
    $displayAffectations = $affectations ?? [];
} else {
    $pageTitle = 'Traçabilité des Affectations & Mouvements (Togo)';
    $pageSubtitle = 'Suivi des fonctionnaires détenteurs, décharges de matériel et mutations administratives au Togo';
    $displayAffectations = $affectations ?? [];
}
$currentPage = 'affectations';
ob_start();
?>

<?php if (!empty($_GET['success'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Opération d'affectation enregistrée avec succès !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['updated'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Affectation modifiée avec succès !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['deleted'])): ?>
    <div class="alert-notification alert-danger alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-trash-can"></i>
            <span>Affectation supprimée avec succès du registre.</span>
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
                    <i class="fa-solid fa-file-contract" style="color: #059669; margin-right: 6px;"></i> Mes Décharges & Dotations Officielles (<?= count($displayAffectations) ?>)
                <?php else: ?>
                    Registre des Affectations du Patrimoine Public (<?= count($displayAffectations) ?>)
                <?php endif; ?>
            </h3>
        </div>
        <div class="card-filters-bar">
            <?php if ($roleKey !== 'agent'): ?>
                <form method="GET" action="/affectations" class="search-form-bar" id="searchFormAff" style="display: flex; align-items: center; gap: 8px;">
                    <div class="search-input-group" style="position: relative;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--color-text-muted);"></i>
                        <input type="text" name="q" id="affSearchInput" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>" placeholder="Rechercher agent, matricule, code..." autocomplete="off" style="padding-right: 32px;">
                        <button type="button" id="clearAffSearch" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; color: var(--color-text-muted); cursor: pointer; font-size: 1.1rem; line-height: 1; <?= empty($_GET['q']) ? 'display: none;' : '' ?>" title="Effacer">&times;</button>
                    </div>
                    <button type="submit" class="btn btn-outline" style="padding: 8px 14px; font-size: 0.82rem;">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span>Rechercher</span>
                    </button>
                    <?php if (!empty($_GET['q'])): ?>
                        <a href="/affectations" class="btn btn-outline" style="padding: 8px 12px; font-size: 0.82rem;" title="Réinitialiser la recherche">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    <?php endif; ?>
                </form>
                <?php if ($roleKey === 'superadmin' || $roleKey === 'gestionnaire'): ?>
                    <button class="btn btn-primary" onclick="document.getElementById('newAffectationModal').classList.add('active');">
                        <i class="fa-solid fa-plus"></i>
                        <span>Nouvelle Affectation</span>
                    </button>
                <?php else: ?>
                    <div style="font-size: 0.82rem; color: #B45309; font-weight: 700; background-color: #FEF3C7; border: 1px solid #FDE68A; padding: 6px 14px; border-radius: 9999px;">
                        <i class="fa-solid fa-clipboard-check"></i> Audit & Contrôle des Décharges (Lecture Seule)
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div style="font-size: 0.82rem; color: #059669; font-weight: 700; background-color: #ECFDF5; border: 1px solid #A7F3D0; padding: 6px 14px; border-radius: 9999px;">
                    <i class="fa-solid fa-stamp"></i> Récépissé Officiel Délivré
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Inventaire</th>
                    <th>Agent / Bénéficiaire (Togo)</th>
                    <th>Matricule & Service</th>
                    <th>Date d'Affectation</th>
                    <th>Motif</th>
                    <th>Statut</th>
                    <th style="min-width: 250px; text-align: center;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($displayAffectations)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 40px; color: var(--color-text-muted);">
                            <p>Aucune affectation active pour le moment dans les administrations togolaises.</p>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($displayAffectations as $aff): ?>
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
                        <td style="text-align: center;">
                            <div class="table-actions">
                                <!-- BOUTON VOIR (TOUS LES RÔLES) -->
                                <button type="button" class="btn btn-info btn-sm" onclick='openViewAffectationModal(<?= json_encode($aff, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Voir la décharge">
                                    <i class="fa-regular fa-eye"></i>
                                    <span><?= $roleKey === 'agent' ? 'Voir Décharge' : 'Voir' ?></span>
                                </button>

                                <?php if ($roleKey === 'agent'): ?>
                                    <!-- ACTION AGENT : VOIR SA DÉCHARGE -->
                                <?php elseif ($roleKey === 'auditeur'): ?>
                                    <!-- AUDITEUR : CONTRÔLE DÉCHARGE VALIDE -->
                                    <span style="font-size: 0.75rem; color: #92400E; font-weight: 600; background: #FEF3C7; padding: 4px 8px; border-radius: 4px; margin-left: 6px;">
                                        <i class="fa-solid fa-shield-halved"></i> Contrôlé IGF
                                    </span>
                                <?php else: ?>
                                    <!-- BOUTON MODIFIER (SUPER-ADMIN & GESTIONNAIRE) -->
                                    <button type="button" class="btn btn-warning btn-sm" onclick='openEditAffectationModal(<?= json_encode($aff, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>)' title="Modifier les données d'affectation">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                        <span>Modifier</span>
                                    </button>

                                    <!-- BOUTON RESTITUER -->
                                    <?php if ($aff['statut'] === 'En cours'): ?>
                                        <form method="POST" action="/affectations/restituer" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $aff['id'] ?>">
                                            <button type="submit" class="btn btn-outline btn-sm" onclick="return confirm('Confirmer la restitution du matériel par l\'agent ?');" title="Clôturer l'affectation">
                                                <i class="fa-solid fa-rotate-left"></i> Restituer
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <!-- BOUTON SUPPRIMER (SUPER-ADMIN UNIQUEMENT) -->
                                    <?php if ($roleKey === 'superadmin'): ?>
                                        <form method="POST" action="/affectations/delete" style="display:inline;" onsubmit="return confirm('Confirmer la suppression de cette affectation pour <?= addslashes($aff['beneficiaire_nom']) ?> ?');">
                                            <input type="hidden" name="id" value="<?= $aff['id'] ?>">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer cette affectation">
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
<!-- 1. MODALE VOIR LA DÉCHARGE OFFICIELLE (CERTIFICAT D'AFFECTATION TOGO)     -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="viewAffectationModal">
    <div class="modal-box" style="max-width: 820px; padding: 26px;">
        <div class="modal-header no-print" style="margin-bottom: 16px;">
            <h3><i class="fa-solid fa-file-signature" style="color: #059669;"></i> Bon Officiel de Décharge : <span id="va_code_title" style="color: var(--color-accent);"></span></h3>
            <button class="modal-close" onclick="document.getElementById('viewAffectationModal').classList.remove('active');">&times;</button>
        </div>

        <div class="modal-body" style="padding: 0;">
            <!-- DOCUMENT IMPRIMABLE A4 DU BON DE DÉCHARGE OFFICIEL (TOGO) -->
            <div id="printableDechargeSheet" class="official-decharge-sheet">
                <!-- En-tête Institutionnel Officiel de la République Togolaise -->
                <div class="decharge-top-header">
                    <div class="rep-header-left">
                        <div class="rep-title">RÉPUBLIQUE TOGOLAISE</div>
                        <div class="rep-devise">Travail - Liberté - Patrie</div>
                        <div class="rep-sep"></div>
                        <div class="rep-min" id="vd_ministere_header">MINISTÈRE DE L'ÉCONOMIE NUMÉRIQUE</div>
                        <div class="rep-dgpe">Direction Générale du Patrimoine de l'État (DGPE)</div>
                    </div>
                    <div class="rep-header-right">
                        <div style="font-size: 1.8rem; color: #0D3B66; margin-bottom: 4px;">
                            <i class="fa-solid fa-landmark"></i>
                        </div>
                        <div class="decharge-ref-box">
                            <div><strong>Lieu & Date :</strong> Lomé, le <span id="vd_date_doc"><?= date('d/m/Y') ?></span></div>
                            <div><strong>Réf. Acte :</strong> <span id="vd_ref_badge">TG-DGPE/DECH-2026</span></div>
                            <div><strong>Statut :</strong> <span id="vd_statut_badge" style="color: #059669; font-weight: 700;">● CONFORME</span></div>
                        </div>
                    </div>
                </div>

                <!-- Bannière du Titre Officiel -->
                <div class="decharge-banner-title">
                    <h2>BON DE DÉCHARGE ET ATTRIBUTION DE MATÉRIEL PUBLIC</h2>
                    <p>Certificat Administratif de Prise en Charge Individuelle et de Dotation d'Usage</p>
                </div>

                <!-- I. Cadre Bénéficiaire -->
                <div class="decharge-block">
                    <div class="decharge-block-title"><i class="fa-solid fa-user-shield"></i> I. CADRE ADMINISTRATIF DU BÉNÉFICIAIRE</div>
                    <table class="decharge-data-table">
                        <tr>
                            <th>Nom & Prénoms de l'Agent :</th>
                            <td><strong id="vd_beneficiaire_nom" style="color: #0D3B66; font-size: 0.95rem;">Afiwa MENSAH</strong></td>
                        </tr>
                        <tr>
                            <th>Matricule Fonctionnaire :</th>
                            <td><span id="vd_beneficiaire_mat" class="code-badge" style="font-size: 0.82rem;">MAT-TG-003</span></td>
                        </tr>
                        <tr>
                            <th>Direction / Service de Rattachement :</th>
                            <td><span id="vd_beneficiaire_serv">Direction de la Transformation Digitale</span></td>
                        </tr>
                    </table>
                </div>

                <!-- II. Signalétique de l'Équipement -->
                <div class="decharge-block">
                    <div class="decharge-block-title"><i class="fa-solid fa-boxes-stacked"></i> II. SIGNALÉTIQUE DU BIEN CONFIÉ</div>
                    <table class="decharge-data-table">
                        <tr>
                            <th>Code Inventaire National :</th>
                            <td><strong id="vd_bien_code" class="code-badge" style="font-size: 0.85rem; background: #0D3B66; color: #FFF;">GPE-000015</strong></td>
                        </tr>
                        <tr>
                            <th>Désignation & Caractéristiques :</th>
                            <td><strong id="vd_bien_libelle" style="color: #059669;">Serveur Rack Dell PowerEdge R750 64GB</strong></td>
                        </tr>
                        <tr>
                            <th>Date de Dotation & Remise :</th>
                            <td><span id="vd_bien_date">25/02/2026</span></td>
                        </tr>
                        <tr>
                            <th>Motif Administratif :</th>
                            <td><span id="vd_bien_motif">Hébergement applicatif ministériel national</span></td>
                        </tr>
                        <tr>
                            <th>État d'Usage Constaté :</th>
                            <td><span id="vd_bien_etat" style="color: #059669; font-weight: 700;">● Bon état (Opérationnel et conforme)</span></td>
                        </tr>
                        <tr>
                            <th>Observations & Remarques :</th>
                            <td><em id="vd_bien_remarques">Matériel neuf certifié conforme avec tous ses accessoires d'origine.</em></td>
                        </tr>
                    </table>
                </div>

                <!-- III. Engagement Déontologique du Fonctionnaire -->
                <div class="decharge-legal-text">
                    <strong>Engagement déontologique du fonctionnaire détenteur :</strong> L'agent bénéficiaire soussigné atteste avoir pris possession du matériel ci-dessus inventorié en parfait état de fonctionnement. Il s'engage formellement à veiller à sa garde diligente, à son entretien régulier et à son affectation exclusive à l'exercice des missions de service public de l'État Togolais. En cas de mutation, congé ou fin de fonction, le bien doit être immédiatement restitué avec visa officiel.
                </div>

                <!-- IV. Double Visa, Cachet Officiel et Signature -->
                <div class="decharge-signatures-grid">
                    <!-- Signature Agent -->
                    <div class="sig-box-card">
                        <div class="sig-box-title">Pour le Bénéficiaire (L'Agent) :</div>
                        <div class="sig-box-sub">Mention manuscrite : <em>« Reçu conforme »</em></div>
                        <div class="sig-box-name" id="vd_sig_agent_name">Mme Afiwa MENSAH</div>
                        <div class="sig-drawing-box">
                            <span class="sig-cursive" id="vd_sig_agent_draw">Afiwa Mensah</span>
                        </div>
                    </div>

                    <!-- Signature Administration + Sceau Officiel de l'État -->
                    <div class="sig-box-card" style="position: relative; overflow: visible;">
                        <div class="sig-box-title">Pour l'Administration (DGPE Togo) :</div>
                        <div class="sig-box-sub"><?= htmlspecialchars($signataireSettings['formule'] ?? 'Pour le Ministre et par ordre,') ?></div>
                        <div class="sig-box-name"><?= htmlspecialchars($signataireSettings['nom'] ?? 'Amele Rolande GOLI') ?></div>
                        <div style="font-size: 0.73rem; color: #64748B;"><?= htmlspecialchars($signataireSettings['titre'] ?? 'Le Directeur Général du Patrimoine') ?></div>
                        
                        <div class="sig-drawing-box" style="justify-content: space-between; align-items: center; position: relative;">
                            <!-- Signature manuscrite scannée -->
                            <div style="z-index: 1;">
                                <?php if (!empty($signatureInfo['url'])): ?>
                                    <img src="<?= htmlspecialchars($signatureInfo['url']) ?>" alt="Signature DGPE" style="max-height: 52px; max-width: 150px; object-fit: contain; transform: rotate(-3deg);">
                                <?php else: ?>
                                    <span class="sig-cursive" style="font-size: 1.8rem;"><?= htmlspecialchars($signataireSettings['nom'] ?? 'Amele Rolande GOLI') ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Cachet / Sceau Officiel Superposé -->
                            <div class="decharge-stamp-overlay">
                                <img src="<?= htmlspecialchars($activeCachetUrl ?? '/uploads/cachet_default.svg') ?>" alt="Cachet Officiel" class="decharge-stamp-img">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bas de page certifié -->
                <div class="decharge-footer-note">
                    GEST-PATRIMOINE TOGO &bull; Récépissé certifié sous l'autorité du Ministère de l'Économie et des Finances &bull; Direction Générale du Patrimoine de l'État
                </div>
            </div>

            <!-- Barre d'actions (masquée à l'impression) -->
            <div class="no-print" style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--color-border);">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('viewAffectationModal').classList.remove('active');">
                    <i class="fa-solid fa-xmark"></i> Fermer
                </button>
                <button type="button" class="btn btn-primary" onclick="window.print();" style="background-color: #059669; border-color: #059669;">
                    <i class="fa-solid fa-print"></i> <strong>Imprimer le Bon de Décharge (A4 / PDF)</strong>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* ========================================================================= */
/* CSS OFFICIEL DU BON DE DÉCHARGE (AFFICHAGE ÉCRAN & IMPRESSION PURE A4)     */
/* ========================================================================= */
.official-decharge-sheet {
    background: #FFFFFF;
    color: #1E293B;
    font-family: 'Plus Jakarta Sans', sans-serif;
    border: 2px solid #0D3B66;
    border-radius: 8px;
    padding: 24px 28px;
    position: relative;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.04);
}
.decharge-top-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    border-bottom: 2px solid #0D3B66;
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.rep-header-left {
    text-align: left;
}
.rep-title {
    font-size: 0.95rem;
    font-weight: 800;
    color: #0D3B66;
    letter-spacing: 0.5px;
}
.rep-devise {
    font-size: 0.78rem;
    font-weight: 700;
    color: #059669;
    margin-top: 2px;
}
.rep-sep {
    width: 65px;
    height: 3px;
    background: linear-gradient(90deg, #059669, #D97706, #DC2626);
    margin: 5px 0;
}
.rep-min {
    font-size: 0.78rem;
    font-weight: 700;
    color: #334155;
    text-transform: uppercase;
}
.rep-dgpe {
    font-size: 0.73rem;
    font-weight: 600;
    color: #64748B;
}
.rep-header-right {
    text-align: right;
    font-size: 0.8rem;
}
.decharge-ref-box {
    background: #F8FAFC;
    border: 1px dashed #CBD5E1;
    padding: 6px 10px;
    border-radius: 6px;
    margin-top: 4px;
    font-size: 0.76rem;
    text-align: right;
}
.decharge-banner-title {
    text-align: center;
    background: linear-gradient(135deg, #0D3B66 0%, #1E3A8A 100%);
    color: #FFFFFF;
    padding: 10px 16px;
    border-radius: 6px;
    margin-bottom: 18px;
}
.decharge-banner-title h2 {
    font-size: 1.05rem;
    font-weight: 800;
    letter-spacing: 0.5px;
    margin: 0;
    color: #FFFFFF;
    text-transform: uppercase;
}
.decharge-banner-title p {
    font-size: 0.74rem;
    opacity: 0.9;
    margin: 2px 0 0 0;
}
.decharge-block {
    margin-bottom: 14px;
}
.decharge-block-title {
    font-size: 0.8rem;
    font-weight: 800;
    color: #0D3B66;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-left: 4px solid #059669;
    padding-left: 8px;
    margin-bottom: 8px;
}
.decharge-data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.decharge-data-table th, .decharge-data-table td {
    border: 1px solid #E2E8F0;
    padding: 6px 10px;
    text-align: left;
}
.decharge-data-table th {
    background-color: #F8FAFC;
    color: #475569;
    font-weight: 700;
    width: 34%;
}
.decharge-data-table td {
    background-color: #FFFFFF;
    color: #0F172A;
}
.decharge-legal-text {
    background: #F0FDF4;
    border: 1px solid #BBF7D0;
    padding: 10px 12px;
    border-radius: 6px;
    font-size: 0.78rem;
    color: #166534;
    line-height: 1.45;
    margin: 14px 0;
}
.decharge-signatures-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
    margin-top: 18px;
    border-top: 1px dashed #CBD5E1;
    padding-top: 14px;
}
.sig-box-card {
    border: 1px solid #E2E8F0;
    border-radius: 6px;
    padding: 10px 12px;
    background: #FAFAFA;
    position: relative;
    min-height: 145px;
}
.sig-box-title {
    font-size: 0.8rem;
    font-weight: 800;
    color: #0D3B66;
    text-transform: uppercase;
}
.sig-box-sub {
    font-size: 0.72rem;
    color: #64748B;
    margin-top: 1px;
}
.sig-box-name {
    font-size: 0.86rem;
    font-weight: 700;
    color: #0D3B66;
    margin-top: 6px;
}
.sig-drawing-box {
    margin-top: 8px;
    position: relative;
    height: 60px;
    display: flex;
    align-items: center;
}
.sig-cursive {
    font-family: 'Brush Script MT', cursive, sans-serif;
    font-size: 1.8rem;
    color: #1E3A8A;
    transform: rotate(-3deg);
}
.decharge-stamp-overlay {
    position: absolute;
    right: 4px;
    bottom: -15px;
    z-index: 2;
    pointer-events: none;
}
.decharge-stamp-img {
    width: 105px;
    height: 105px;
    object-fit: contain;
    transform: rotate(-6deg);
    opacity: 0.95;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.08));
}
.decharge-footer-note {
    text-align: center;
    font-size: 0.7rem;
    color: #94A3B8;
    margin-top: 14px;
    border-top: 1px solid #F1F5F9;
    padding-top: 6px;
}

/* RÈGLES D'IMPRESSION A4 PARFAITES (@media print) */
@media print {
    body {
        background: #FFFFFF !important;
        margin: 0 !important;
        padding: 0 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    .app-wrapper, .app-sidebar, .app-topbar, .no-print, .modal-header, .alert-notification, .card-header-bar, .card-filters-bar, .table-responsive {
        display: none !important;
    }
    .modal-overlay {
        position: static !important;
        background: transparent !important;
        padding: 0 !important;
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }
    .modal-box {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .modal-body {
        padding: 0 !important;
    }
    #printableDechargeSheet {
        display: block !important;
        visibility: visible !important;
        border: 2px solid #000000 !important;
        box-shadow: none !important;
        margin: 0 auto !important;
        padding: 14mm 16mm !important;
        page-break-after: avoid !important;
        max-width: 100% !important;
    }
    .decharge-banner-title {
        background: #0D3B66 !important;
        color: #FFFFFF !important;
    }
    .decharge-data-table th {
        background-color: #F1F5F9 !important;
    }
}
</style>

<!-- ========================================================================= -->
<!-- 2. MODALE MODIFIER L'AFFECTATION                                           -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="editAffectationModal">
    <div class="modal-box" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square" style="color: var(--color-warning);"></i> Modifier l'Affectation : <span id="ea_code_title"></span></h3>
            <button class="modal-close" onclick="document.getElementById('editAffectationModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/affectations/update" class="modal-body">
            <input type="hidden" name="id" id="ea_id">

            <div class="form-row">
                <div class="form-group">
                    <label>Nom & Prénoms de l'Agent (Togo) *</label>
                    <input type="text" name="beneficiaire_nom" id="ea_beneficiaire_nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Matricule Fonctionnaire *</label>
                    <input type="text" name="beneficiaire_matricule" id="ea_beneficiaire_matricule" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Direction ou Service de rattachement *</label>
                    <input type="text" name="service_nom" id="ea_service_nom" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Date d'Affectation *</label>
                    <input type="date" name="date_affectation" id="ea_date_affectation" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Motif de l'Affectation *</label>
                    <input type="text" name="motif" id="ea_motif" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Statut *</label>
                    <select name="statut" id="ea_statut" class="form-control">
                        <option value="En cours">En cours</option>
                        <option value="Restitué">Restitué</option>
                        <option value="Transféré">Transféré</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Observations / Décharge</label>
                <textarea name="remarques" id="ea_remarques" class="form-control" rows="2"></textarea>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editAffectationModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-save"></i> Enregistrer les Modifications</button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- 3. MODALE NOUVELLE AFFECTATION (TOGO)                                      -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="newAffectationModal">
    <div class="modal-box" style="max-width: 650px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-user-plus" style="color: var(--color-primary);"></i> Attribuer un Bien à un Agent ou Service (Togo)</h3>
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
                    <input type="text" name="beneficiaire_nom" class="form-control" placeholder="Ex: M. Kodjo AGBEKO ou Mme Afiwa MENSAH" required>
                </div>
                <div class="form-group">
                    <label>Matricule Fonctionnaire (Togo) *</label>
                    <input type="text" name="beneficiaire_matricule" class="form-control" placeholder="Ex: MAT-TG-84920" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Direction ou Service de rattachement *</label>
                    <input type="text" name="service_nom" class="form-control" placeholder="Ex: Direction Générale du Trésor - Lomé" required>
                </div>
                <div class="form-group">
                    <label>Date de Mise à Disposition</label>
                    <input type="date" name="date_affectation" class="form-control" value="<?= date('Y-m-d') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label>Motif de l'Affectation *</label>
                <input type="text" name="motif" class="form-control" placeholder="Ex: Véhicule de fonction ou dotation informatique de service" required>
            </div>

            <div class="form-group">
                <label>Remarques / Référence Décharge</label>
                <input type="text" name="remarques" class="form-control" placeholder="Ex: Décharge signée n° 2026/DGPE/04">
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

function openViewAffectationModal(aff) {
    const code = aff.code_inventaire || 'GPE-000015';
    const nom = aff.beneficiaire_nom || 'Afiwa MENSAH';
    const mat = aff.beneficiaire_matricule || 'MAT-TG-003';
    const serv = aff.service_nom || 'Direction de la Transformation Digitale';
    const dateAff = aff.date_affectation ? aff.date_affectation.split('-').reverse().join('/') : '25/02/2026';
    const motif = aff.motif || 'Affectation administrative de dotation individuelle';
    const remarques = aff.remarques || 'Matériel certifié conforme, en parfait état d\'usage et de marche.';

    // Titres et badges
    const titleEl = document.getElementById('va_code_title');
    if (titleEl) titleEl.innerText = code;
    const refBadge = document.getElementById('vd_ref_badge');
    if (refBadge) refBadge.innerText = 'TG-DGPE/' + code + '/2026';
    const statutBadge = document.getElementById('vd_statut_badge');
    if (statutBadge) statutBadge.innerText = '● ' + (aff.statut || 'CONFORME').toUpperCase();

    // Bénéficiaire
    const benNom = document.getElementById('vd_beneficiaire_nom');
    if (benNom) benNom.innerText = nom;
    const benMat = document.getElementById('vd_beneficiaire_mat');
    if (benMat) benMat.innerText = mat;
    const benServ = document.getElementById('vd_beneficiaire_serv');
    if (benServ) benServ.innerText = serv;

    // Matériel
    const bienCode = document.getElementById('vd_bien_code');
    if (bienCode) bienCode.innerText = code;
    const bienDate = document.getElementById('vd_bien_date');
    if (bienDate) bienDate.innerText = dateAff;
    const bienMotif = document.getElementById('vd_bien_motif');
    if (bienMotif) bienMotif.innerText = motif;
    const bienRem = document.getElementById('vd_bien_remarques');
    if (bienRem) bienRem.innerText = remarques;

    // Libellé adapté
    const bienLib = document.getElementById('vd_bien_libelle');
    if (bienLib) {
        if (code === 'GPE-000015') {
            bienLib.innerText = 'Serveur Rack Dell PowerEdge R750 64GB';
        } else if (code === 'GPE-000014') {
            bienLib.innerText = 'Toyota Hilux Double Cabine 4x4 2.8 D-4D';
        } else if (code === 'GPE-000016') {
            bienLib.innerText = 'Immeuble Administratif R+5 Cité OUA';
        } else if (code === 'GPE-000013') {
            bienLib.innerText = 'Lot Sanitaire & Robinetterie Institutionnelle';
        } else {
            bienLib.innerText = aff.libelle || 'Matériel de l\'État Togolais';
        }
    }

    // Signatures
    const sigAgentName = document.getElementById('vd_sig_agent_name');
    if (sigAgentName) sigAgentName.innerText = 'M./Mme ' + nom;
    const sigAgentDraw = document.getElementById('vd_sig_agent_draw');
    if (sigAgentDraw) sigAgentDraw.innerText = nom;

    document.getElementById('viewAffectationModal').classList.add('active');
}

function openEditAffectationModal(aff) {
    document.getElementById('ea_code_title').innerText = aff.code_inventaire || '';
    document.getElementById('ea_id').value = aff.id || '';
    document.getElementById('ea_beneficiaire_nom').value = aff.beneficiaire_nom || '';
    document.getElementById('ea_beneficiaire_matricule').value = aff.beneficiaire_matricule || '';
    document.getElementById('ea_service_nom').value = aff.service_nom || '';
    document.getElementById('ea_date_affectation').value = aff.date_affectation || '';
    document.getElementById('ea_motif').value = aff.motif || '';
    document.getElementById('ea_statut').value = aff.statut || 'En cours';
    document.getElementById('ea_remarques').value = aff.remarques || '';

    document.getElementById('editAffectationModal').classList.add('active');
}

// --- FILTRAGE EN TEMPS RÉEL (Live Search Affectations) ---
const affSearch = document.getElementById('affSearchInput');
const clearAffBtn = document.getElementById('clearAffSearch');
const affTitle = document.querySelector('.card-header-title');
const originalAffTitle = affTitle ? affTitle.innerText : '';

function filterAffTable(query) {
    query = query.toLowerCase().trim();
    if (clearAffBtn) {
        clearAffBtn.style.display = query.length > 0 ? 'block' : 'none';
    }

    const rows = document.querySelectorAll('.app-table tbody tr');
    let visibleCount = 0;
    let noResultRow = document.getElementById('dynamicNoResultAffRow');

    rows.forEach(row => {
        if (row.id === 'dynamicNoResultAffRow') return;

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
            noResultRow.id = 'dynamicNoResultAffRow';
            noResultRow.innerHTML = '<td colspan="7" style="text-align: center; padding: 40px; color: var(--color-text-muted);"><i class="fa-solid fa-magnifying-glass" style="font-size: 2rem; margin-bottom: 10px; display:block;"></i><strong>Aucune affectation trouvée pour "' + query.replace(/</g, '&lt;') + '"</strong></td>';
            document.querySelector('.app-table tbody').appendChild(noResultRow);
        } else {
            noResultRow.style.display = '';
            noResultRow.querySelector('strong').innerText = 'Aucune affectation trouvée pour "' + query + '"';
        }
    } else if (noResultRow) {
        noResultRow.style.display = 'none';
    }

    if (affTitle) {
        if (query === '') {
            affTitle.innerText = originalAffTitle;
        } else {
            affTitle.innerText = 'Registre des Affectations du Patrimoine Public (' + visibleCount + ' résultat' + (visibleCount > 1 ? 's' : '') + ')';
        }
    }
}

if (affSearch) {
    affSearch.addEventListener('input', function() {
        filterAffTable(this.value);
    });

    if (clearAffBtn) {
        clearAffBtn.addEventListener('click', function() {
            affSearch.value = '';
            filterAffTable('');
            affSearch.focus();
            if (window.location.search.includes('q=')) {
                window.location.href = '/affectations';
            }
        });
    }
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>