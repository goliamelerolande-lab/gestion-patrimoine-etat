<?php
$pageTitle = 'Rapports Globaux du Patrimoine (Togo)';
$pageSubtitle = 'Analyse détaillée, certification administrative et exportation des données du patrimoine national';
$currentPage = 'rapports';
ob_start();
?>

<!-- Alertes d'état -->
<?php if (!empty($_GET['settings_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Paramètres du signataire (nom, titre, signature) mis à jour avec succès sur tous les documents officiels !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sig_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Signature manuscrite importée et apposée avec succès !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sig_reset'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-rotate-left"></i>
            <span>Signature personnalisée réinitialisée au format standard.</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['cachet_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check"></i>
            <span>Le Cachet Officiel a été téléversé et apposé avec succès sur tous vos rapports administratifs !</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['cachet_reset'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-rotate-left"></i>
            <span>Le Cachet Officiel standard de la République Togolaise a été rétabli.</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['error_cachet']) || !empty($_GET['error_sig'])): ?>
    <div class="alert-notification alert-danger alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span>Format d'image invalide. Veuillez sélectionner une image PNG, JPG ou SVG.</span>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<!-- Boutons d'actions supérieurs -->
<div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 24px;" class="no-print">
    <a href="/cachet" class="btn btn-outline" style="gap: 8px;">
        <i class="fa-solid fa-stamp" style="color: var(--color-primary-light);"></i>
        <span>Gérer / Importer le Cachet</span>
    </a>
    <button class="btn btn-primary" onclick="window.print();" style="gap: 8px;">
        <i class="fa-solid fa-print"></i>
        <span>Imprimer / Exporter en PDF</span>
    </button>
</div>

<!-- Bloc officiel de rapport (format certifié conforme DGPE Togo) -->
<div class="report-container">
    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 24px; border-bottom: 2px solid var(--color-primary); padding-bottom: 16px;">
        <div>
            <div style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: var(--color-primary); letter-spacing: 1px;">RÉPUBLIQUE TOGOLAISE</div>
            <div style="font-size: 0.75rem; color: #059669; font-weight: 700; margin-bottom: 8px;">Travail - Liberté - Patrie</div>
            <h1 style="font-size: 1.8rem; font-weight: 800; color: var(--color-primary); margin-bottom: 4px;">Rapports Globaux du Patrimoine</h1>
            <p style="color: var(--color-text-muted); font-size: 0.95rem;">Analyse détaillée et valorisation officielle du registre national de l'État Togolais</p>
        </div>
        <div style="text-align: right; font-size: 0.82rem; color: var(--color-text-muted);">
            <div style="font-weight: 700; color: var(--color-primary);">Direction Générale du Patrimoine de l'État</div>
            <div>Ministère de l'Économie et des Finances (Lomé - Togo)</div>
            <div style="margin-top: 6px;"><strong style="background: #E2E8F0; padding: 3px 8px; border-radius: 4px;">Rapports Officiels - DGPE Togo</strong></div>
        </div>
    </div>

    <!-- 1. Cartes de Synthèse du Rapport -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 24px;">
        <!-- Total des Biens -->
        <div style="background: #FFFFFF; border-radius: 14px; padding: 24px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Total des Biens</div>
                <div style="font-size: 2.4rem; font-weight: 800; color: var(--color-primary); line-height: 1.2; margin-top: 6px;">
                    <?= number_format($stats['total_biens'] ?? count($biens), 0, ',', ' ') ?>
                </div>
            </div>
            <div style="font-size: 2rem; color: #CBD5E1;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Valeur Cumulée -->
        <div style="background: #FFFFFF; border-radius: 14px; padding: 24px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Valeur Cumulée</div>
                <div style="font-size: 2.1rem; font-weight: 800; color: #059669; line-height: 1.2; margin-top: 6px;">
                    <?= number_format($stats['valeur_cumulee_fcfa'] ?? 0, 0, ',', ' ') ?> <small style="font-size: 1rem;">FCFA</small>
                </div>
            </div>
            <div style="font-size: 2rem; color: #10B981;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>

        <!-- Date du rapport -->
        <div style="background: #FFFFFF; border-radius: 14px; padding: 24px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <div style="font-size: 0.85rem; font-weight: 600; color: var(--color-text-muted); text-transform: uppercase;">Date du rapport</div>
                <div style="font-size: 2.1rem; font-weight: 800; color: var(--color-primary); line-height: 1.2; margin-top: 6px;">
                    <?= date('d/m/Y') ?>
                </div>
            </div>
            <div style="font-size: 2rem; color: #F59E0B;">
                <i class="fa-regular fa-calendar-days"></i>
            </div>
        </div>
    </div>

    <!-- 2. Répartition globale par état -->
    <div style="background: #FFFFFF; border-radius: 14px; padding: 24px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); margin-bottom: 28px;">
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-primary); margin-bottom: 4px;">Répartition globale par état d'usage</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px;">Totaux calculés sur l’ensemble du registre national togolais.</p>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
            <?php foreach ($stats['repartition_etat'] as $rep): ?>
            <div style="border: 1px solid var(--color-border); border-radius: 10px; padding: 18px; background-color: #F8FAFC;">
                <div style="font-size: 0.78rem; font-weight: 700; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 6px;">
                    <?= htmlspecialchars($rep['etat']) ?>
                </div>
                <div style="font-size: 1.5rem; font-weight: 800; color: var(--color-primary); margin-bottom: 4px;">
                    <?= $rep['count'] ?> bien(s)
                </div>
                <div style="font-size: 0.95rem; font-weight: 700; color: #059669;">
                    <?= number_format($rep['valeur_totale'], 0, ',', ' ') ?> FCFA
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- 3. Tableau des biens enregistrés -->
    <div style="background: #FFFFFF; border-radius: 14px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--color-border);">
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--color-primary);">Biens récents du registre togolais</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted);">Extrait officiel de l'inventaire permanent</p>
        </div>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th style="font-size: 0.8rem;">CODE INVENTAIRE</th>
                        <th style="font-size: 0.8rem;">LIBELLÉ DU BIEN</th>
                        <th style="font-size: 0.8rem;">DATE D'ACQUISITION</th>
                        <th style="font-size: 0.8rem;">VALEUR (FCFA)</th>
                        <th style="font-size: 0.8rem;">ÉTAT</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($biens, 0, 20) as $b): ?>
                    <tr>
                        <td>
                            <span class="code-badge" style="font-size: 0.95rem; padding: 6px 12px;"><?= htmlspecialchars($b['code_inventaire']) ?></span>
                        </td>
                        <td>
                            <strong style="font-size: 1.05rem;"><?= htmlspecialchars($b['libelle']) ?></strong>
                            <div style="font-size: 0.8rem; color: var(--color-text-muted);"><?= htmlspecialchars($b['ministere']) ?> &bull; <?= htmlspecialchars($b['localisation']) ?></div>
                        </td>
                        <td style="font-size: 0.95rem;"><?= date('d/m/Y', strtotime($b['date_acquisition'])) ?></td>
                        <td>
                            <span style="font-weight: 800; font-size: 1.1rem; color: #059669;">
                                <?= number_format($b['valeur_acquisition_fcfa'], 0, ',', ' ') ?> FCFA
                            </span>
                        </td>
                        <td>
                            <span class="status-pill status-neuf" style="font-size: 0.85rem;"><?= htmlspecialchars($b['etat']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- 4. Visa officiel, Signature et Cachet Importé -->
    <div style="margin-top: 30px; display: flex; justify-content: space-between; align-items: flex-end; padding: 24px; background: #F8FAFC; border-radius: 12px; border: 1px dashed var(--color-border); position: relative;" class="print-border">
        <div>
            <div style="font-size: 0.82rem; font-weight: 800; color: var(--color-primary); letter-spacing: 0.5px;">RÉPUBLIQUE TOGOLAISE &bull; MINISTÈRE DE L'ÉCONOMIE ET DES FINANCES</div>
            <div style="font-size: 0.78rem; color: var(--color-text-muted); margin-top: 3px;">Certification officielle conforme aux normes comptables et administratives togolaises</div>
            <div style="font-size: 0.78rem; color: var(--color-text-muted);">Direction Générale du Patrimoine de l'État (DGPE - Lomé)</div>
            
            <div style="margin-top: 14px; display: flex; gap: 8px; flex-wrap: wrap;" class="no-print">
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('uploadSignatureModal').classList.add('active');" style="font-size: 0.78rem; gap: 6px;">
                    <i class="fa-solid fa-pen-nib" style="color: var(--color-primary-light);"></i> Paramètres Signataire & Signature
                </button>
                <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('uploadCachetModal').classList.add('active');" style="font-size: 0.78rem; gap: 6px;">
                    <i class="fa-solid fa-stamp" style="color: #059669;"></i> <?= $hasCustomCachet ? 'Modifier le Cachet' : 'Importer le Cachet' ?>
                </button>
                <a href="/cachet" class="btn btn-outline btn-sm" style="font-size: 0.78rem; gap: 6px;">
                    <i class="fa-solid fa-sliders"></i> Ouvrir les Paramètres Complets
                </a>
            </div>
        </div>

        <div style="text-align: right; position: relative; min-width: 290px;">
            <div style="font-size: 0.82rem; font-weight: 600;">Fait à <?= htmlspecialchars($signataireSettings['lieu']) ?>, le <?= date('d/m/Y') ?></div>
            <div style="font-size: 0.8rem; font-weight: 700; color: var(--color-primary); margin-top: 4px;"><?= htmlspecialchars($signataireSettings['formule']) ?></div>
            <div style="font-size: 0.85rem; font-weight: 800; color: var(--color-primary); margin-top: 2px;"><?= htmlspecialchars($signataireSettings['titre']) ?></div>
            
            <!-- NOM DU SIGNATAIRE AU-DESSUS DE LA SIGNATURE (PERSONNALISABLE DANS LES PARAMÈTRES) -->
            <div style="font-size: 0.95rem; font-weight: 800; color: #0D3B66; margin-top: 5px; letter-spacing: 0.3px;">
                <?= htmlspecialchars($signataireSettings['nom']) ?>
            </div>

            <!-- ZONE DE SIGNATURE (IMAGE NUMÉRISÉE IMPORTÉE OU CALLIGRAPHIE DU NOM) -->
            <div style="height: 68px; display: flex; align-items: center; justify-content: flex-end; position: relative; margin-top: 4px; margin-bottom: -15px;">
                <?php if (!empty($signatureInfo['is_custom'])): ?>
                    <img src="<?= htmlspecialchars($signatureInfo['url']) ?>" alt="Signature de <?= htmlspecialchars($signataireSettings['nom']) ?>" style="max-height: 60px; max-width: 180px; object-fit: contain; transform: rotate(-3deg); filter: drop-shadow(0 1px 2px rgba(0,0,0,0.1));">
                <?php else: ?>
                    <div style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 2.1rem; color: #1E3A8A; transform: rotate(-5deg); pointer-events: none;">
                        <?= htmlspecialchars($signataireSettings['nom']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- ZONE D'AFFICHAGE DU CACHET OFFICIEL SUPERPOSÉ (CLIQUABLE) -->
            <div style="display: flex; justify-content: flex-end; align-items: center; margin-top: -15px; position: relative; z-index: 2;">
                <div style="position: relative; display: inline-block; cursor: pointer;" onclick="document.getElementById('uploadCachetModal').classList.add('active');" title="Cliquez pour changer le cachet officiel">
                    <img id="rapportCachetImg" src="<?= htmlspecialchars($activeCachetUrl) ?>" alt="Cachet Officiel" style="max-width: 140px; max-height: 140px; object-fit: contain; transform: rotate(-5deg); filter: drop-shadow(0 2px 5px rgba(0,0,0,0.08));">
                </div>
            </div>

            <div style="margin-top: 6px;">
                <span style="font-family: monospace; font-size: 0.75rem; color: #059669; font-weight: 700; border: 1px solid #10B981; padding: 4px 10px; border-radius: 4px; display: inline-block; background: #ECFDF5;">
                    <i class="fa-solid fa-certificate"></i> SCEAU &bull; DGPE RÉPUBLIQUE TOGOLAISE
                </span>
            </div>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODALE 1 : PARAMÈTRES DU SIGNATAIRE & SIGNATURE MANUSCRITE                -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="uploadSignatureModal">
    <div class="modal-box" style="max-width: 620px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-nib" style="color: var(--color-primary-light);"></i> Paramètres du Signataire & Signature</h3>
            <button class="modal-close" onclick="document.getElementById('uploadSignatureModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/parametres/save-signataire" enctype="multipart/form-data" class="modal-body">
            <input type="hidden" name="redirect_to" value="/rapports">
            
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 18px;">
                Saisissez le nom et le titre qui apparaîtront <strong>au-dessus de la signature</strong> sur tous les documents officiels, et importez facultativement un scan de votre signature manuscrite.
            </p>

            <div class="form-group">
                <label style="font-weight: 700;">Nom complet de l'Autorité Signataire (affiché au-dessus de la signature) *</label>
                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($signataireSettings['nom']) ?>" required placeholder="Ex: Amele Rolande GOLI ou Koffi MENSAH-ADJALLÉ">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label style="font-weight: 700;">Titre / Fonction *</label>
                    <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($signataireSettings['titre']) ?>" required placeholder="Ex: Le Directeur Général du Patrimoine">
                </div>
                <div class="form-group">
                    <label style="font-weight: 700;">Formule de Délégation *</label>
                    <input type="text" name="formule" class="form-control" value="<?= htmlspecialchars($signataireSettings['formule']) ?>" required placeholder="Ex: Pour le Ministre et par ordre,">
                </div>
            </div>

            <div class="form-group">
                <label style="font-weight: 700;">Lieu / Ville d'émission *</label>
                <input type="text" name="lieu" class="form-control" value="<?= htmlspecialchars($signataireSettings['lieu']) ?>" required placeholder="Ex: Lomé">
            </div>

            <div class="form-group" style="margin-top: 18px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <label style="font-weight: 700;"><i class="fa-solid fa-cloud-arrow-up"></i> Importer une Image de Signature Manuscrite (Optionnel)</label>
                <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-bottom: 8px;">
                    Téléversez un scan de signature (PNG avec fond transparent recommandé, ou JPG). Si aucune image n'est choisie, une signature calligraphique au nom indiqué ci-dessus sera générée.
                </p>
                <input type="file" name="signature_file" class="form-control" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp">
                
                <?php if (!empty($signatureInfo['is_custom'])): ?>
                    <div style="margin-top: 10px; display: flex; align-items: center; justify-content: space-between; background: #ECFDF5; padding: 8px 12px; border-radius: 6px; font-size: 0.8rem;">
                        <span style="color: #065F46; font-weight: 600;"><i class="fa-solid fa-circle-check"></i> Signature personnalisée active (<?= htmlspecialchars($signatureInfo['filename']) ?>)</span>
                        <button type="button" class="btn btn-outline btn-sm" onclick="document.getElementById('formResetSigRapports').submit();" style="color: #B91C1C; padding: 3px 8px; font-size: 0.75rem;">
                            Supprimer
                        </button>
                    </div>
                <?php endif; ?>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('uploadSignatureModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">
                    <i class="fa-solid fa-check"></i> Enregistrer les Paramètres & Signature
                </button>
            </div>
        </form>
        <form method="POST" action="/signature/reset" id="formResetSigRapports" style="display: none;">
            <input type="hidden" name="redirect_to" value="/rapports">
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODALE 2 : IMPORTATION DU CACHET OFFICIEL                                 -->
<!-- ========================================================================= -->
<div class="modal-overlay <?= (!empty($_GET['modal']) && $_GET['modal'] === 'cachet') ? 'active' : '' ?>" id="uploadCachetModal">
    <div class="modal-box" style="max-width: 580px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-stamp" style="color: var(--color-primary-light);"></i> Importer le Cachet / Sceau Officiel</h3>
            <button class="modal-close" onclick="document.getElementById('uploadCachetModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/rapports/upload-cachet" enctype="multipart/form-data" class="modal-body">
            <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 16px;">
                Téléversez l'image officielle du tampon encreur ou du sceau administratif de la République Togolaise (formats acceptés : <strong>PNG transparent</strong>, JPG, SVG).
            </p>

            <!-- Zone de prévisualisation du cachet -->
            <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px; background: #F8FAFC; border: 2px dashed var(--color-border); border-radius: 12px; padding: 20px;">
                <div style="width: 130px; height: 130px; background: #FFFFFF; border-radius: 10px; border: 1px solid var(--color-border); display: flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 2px 6px rgba(0,0,0,0.04);">
                    <img id="cachetModalPreview" src="<?= htmlspecialchars($activeCachetUrl) ?>" alt="Aperçu Cachet" style="max-width: 110px; max-height: 110px; object-fit: contain; transform: rotate(-4deg);">
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--color-primary); margin-bottom: 4px;">Cachet Actif sur les Rapports</div>
                    <div style="font-size: 0.8rem; color: var(--color-text-muted); line-height: 1.4;">
                        <?= $hasCustomCachet ? '<span style="color:#059669; font-weight:700;"><i class="fa-solid fa-circle-check"></i> Cachet personnalisé importé</span>' : '<span style="color:#2563EB; font-weight:700;"><i class="fa-solid fa-shield"></i> Sceau officiel standard Togo</span>' ?>
                    </div>
                    <div style="font-size: 0.75rem; color: var(--color-text-muted); margin-top: 6px;">
                        Apposé automatiquement en bas de page sur toutes les fiches d'inventaire et impressions PDF officielles.
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label style="font-weight: 700;">Sélectionner le fichier du cachet (PNG, JPG, SVG) *</label>
                <input type="file" name="cachet_file" id="cachetFileInput" class="form-control" accept="image/png, image/jpeg, image/jpg, image/svg+xml, image/webp" required>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 24px;">
                <div>
                    <?php if ($hasCustomCachet): ?>
                        <button type="button" class="btn btn-outline btn-sm" onclick="if(confirm('Rétablir le cachet officiel standard de la République Togolaise ?')) { document.getElementById('formResetCachet').submit(); }" style="color: var(--color-danger); border-color: var(--color-danger-bg);">
                            <i class="fa-solid fa-rotate-left"></i> Rétablir cachet par défaut
                        </button>
                    <?php endif; ?>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button type="button" class="btn btn-outline" onclick="document.getElementById('uploadCachetModal').classList.remove('active');">Annuler</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-cloud-arrow-up"></i> Enregistrer le Cachet</button>
                </div>
            </div>
        </form>

        <form method="POST" action="/rapports/reset-cachet" id="formResetCachet" style="display: none;"></form>
    </div>
</div>

<script>
// Prévisualisation immédiate lors du choix de fichier
document.getElementById('cachetFileInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(evt) {
            document.getElementById('cachetModalPreview').src = evt.target.result;
            const repImg = document.getElementById('rapportCachetImg');
            if (repImg) repImg.src = evt.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>