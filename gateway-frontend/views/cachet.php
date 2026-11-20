<?php
$pageTitle = 'Paramètres Signataire, Signature & Cachet (Togo)';
$pageSubtitle = 'Configuration des mentions officielles, du nom au-dessus de la signature, de la signature manuscrite numérisée et du sceau d\'État';
$currentPage = 'cachet';
ob_start();
?>

<!-- Alertes d'état -->
<?php if (!empty($_GET['settings_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Paramètres enregistrés avec succès !</strong><br>
                Le nom du signataire, sa fonction et sa signature ont été mis à jour sur l'ensemble des documents administratifs et rapports.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sig_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Signature manuscrite importée avec succès !</strong><br>
                L'image numérisée de la signature est désormais apposée sur tous les actes et rapports officiels.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['sig_reset'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-rotate-left" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Signature réinitialisée !</strong><br>
                La signature manuscrite personnalisée a été retirée au profit de la calligraphie générée avec le nom officiel.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['cachet_saved'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Cachet officiel mis à jour avec succès !</strong><br>
                Le nouveau sceau administratif a été déployé et est immédiatement apposé sur tous vos rapports et bordereaux officiels.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['cachet_reset'])): ?>
    <div class="alert-notification alert-warning alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-rotate-left" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Sceau officiel par défaut rétabli !</strong><br>
                Le sceau officiel standard de la République Togolaise (Direction Générale du Patrimoine de l'État) est désormais actif.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<?php if (!empty($_GET['error_cachet']) || !empty($_GET['error_sig'])): ?>
    <div class="alert-notification alert-danger alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Format de fichier non valide !</strong><br>
                Veuillez sélectionner une image valide (PNG avec fond transparent, SVG, JPG ou WEBP) de moins de 5 Mo.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<!-- Boutons d'actions rapides -->
<div style="display: flex; justify-content: flex-end; gap: 12px; margin-bottom: 24px;" class="no-print">
    <a href="/rapports" class="btn btn-outline" style="gap: 8px;">
        <i class="fa-solid fa-file-invoice-dollar"></i>
        <span>Consulter les Rapports Globaux</span>
    </a>
    <button class="btn btn-primary" onclick="window.print();" style="gap: 8px;">
        <i class="fa-solid fa-print"></i>
        <span>Imprimer Spécimen Officiel</span>
    </button>
</div>

<!-- Grille Principale : Paramètres & Aperçu Réel -->
<div class="cachet-grid-layout">
    
    <!-- Colonne GAUCHE : Formulaires de Paramétrage -->
    <div style="display: flex; flex-direction: column; gap: 24px;">
        
        <!-- CARTE 1 : Paramètres du Signataire (Nom au-dessus de la signature, Titre, Lieu) -->
        <div class="data-card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin-bottom: 2px;">
                        <i class="fa-solid fa-user-pen" style="color: var(--color-primary-light); margin-right: 8px;"></i> Identité de l'Autorité Signataire
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--color-text-muted);">
                        Configurez le nom affiché <strong>au-dessus de la signature</strong> sur tous les documents.
                    </p>
                </div>
                <span class="badge-official" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; font-weight: 700;">
                    <i class="fa-solid fa-check"></i> Actif
                </span>
            </div>

            <form action="/parametres/save-signataire" method="POST" id="formSignataireSettings">
                <input type="hidden" name="redirect_to" value="/cachet">

                <div class="form-group">
                    <label style="font-weight: 700; color: #0D3B66;">
                        Nom complet du Signataire (qui apparaîtra au-dessus de la signature) *
                    </label>
                    <input type="text" name="nom" id="inputSignataireNom" class="form-control" value="<?= htmlspecialchars($signataireSettings['nom']) ?>" required placeholder="Ex: Amele Rolande GOLI" oninput="updateLiveSimulator();" style="font-weight: 700; font-size: 1rem; color: #0D3B66; border-color: #93C5FD;">
                    <small style="color: var(--color-text-muted); font-size: 0.76rem; margin-top: 4px; display: block;">
                        Ce nom est récupéré et affiché directement au-dessus de l'empreinte de la signature sur tous les bordereaux et rapports.
                    </small>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label style="font-weight: 700;">Titre / Fonction *</label>
                        <input type="text" name="titre" id="inputSignataireTitre" class="form-control" value="<?= htmlspecialchars($signataireSettings['titre']) ?>" required placeholder="Ex: Le Directeur Général du Patrimoine" oninput="updateLiveSimulator();">
                    </div>
                    <div class="form-group">
                        <label style="font-weight: 700;">Formule de Délégation *</label>
                        <input type="text" name="formule" id="inputSignataireFormule" class="form-control" value="<?= htmlspecialchars($signataireSettings['formule']) ?>" required placeholder="Ex: Pour le Ministre et par ordre," oninput="updateLiveSimulator();">
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-weight: 700;">Lieu / Ville d'émission *</label>
                    <input type="text" name="lieu" id="inputSignataireLieu" class="form-control" value="<?= htmlspecialchars($signataireSettings['lieu']) ?>" required placeholder="Ex: Lomé" oninput="updateLiveSimulator();">
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 18px;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; gap: 8px;">
                        <i class="fa-solid fa-floppy-disk"></i> Enregistrer le Nom & les Coordonnées
                    </button>
                </div>
            </form>
        </div>

        <!-- CARTE 2 : Importer la Signature Manuscrite (Image numérisée) -->
        <div class="data-card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin-bottom: 2px;">
                        <i class="fa-solid fa-signature" style="color: #2563EB; margin-right: 8px;"></i> Importer la Signature Manuscrite
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--color-text-muted);">
                        Téléversez le scan officiel de la signature manuscrite pour remplacer la calligraphie automatique.
                    </p>
                </div>
                <div>
                    <?php if (!empty($signatureInfo['is_custom'])): ?>
                        <span class="badge-official" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; font-weight: 700;">
                            <i class="fa-solid fa-check-circle"></i> Signature Scannée Active
                        </span>
                    <?php else: ?>
                        <span class="badge-official" style="background-color: #EFF6FF; border-color: #BFDBFE; color: #1D4ED8; font-weight: 700;">
                            <i class="fa-solid fa-pen"></i> Calligraphie Automatique
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Aperçu actuel de la signature -->
            <div style="display: flex; align-items: center; gap: 20px; background: #F8FAFC; border: 1px solid var(--color-border); border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                <div style="width: 130px; height: 75px; background: #FFFFFF; border: 1px solid var(--color-border); border-radius: 8px; display: flex; align-items: center; justify-content: center; overflow: hidden; padding: 6px;">
                    <?php if (!empty($signatureInfo['is_custom'])): ?>
                        <img src="<?= htmlspecialchars($signatureInfo['url']) ?>" alt="Signature" id="currentSigImg" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <div id="currentSigCalligraphy" style="font-family: 'Brush Script MT', cursive, sans-serif; font-size: 1.6rem; color: #1E3A8A; transform: rotate(-5deg); text-align: center;">
                            <?= htmlspecialchars($signataireSettings['nom']) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div style="flex: 1; font-size: 0.82rem;">
                    <?php if (!empty($signatureInfo['is_custom'])): ?>
                        <div style="font-weight: 700; color: var(--color-primary);"><?= htmlspecialchars($signatureInfo['filename']) ?> (<?= htmlspecialchars($signatureInfo['filesize']) ?>)</div>
                        <div style="color: var(--color-text-muted); margin-top: 2px;">Dernière mise à jour : <?= htmlspecialchars($signatureInfo['updated_at']) ?></div>
                        <form action="/signature/reset" method="POST" style="margin-top: 8px;" onsubmit="return confirm('Retirer la signature personnalisée et revenir à la calligraphie générée ?');">
                            <input type="hidden" name="redirect_to" value="/cachet">
                            <button type="submit" class="btn btn-outline" style="color: #B91C1C; border-color: #FECACA; padding: 4px 10px; font-size: 0.78rem;">
                                <i class="fa-solid fa-trash-can"></i> Supprimer cette image de signature
                            </button>
                        </form>
                    <?php else: ?>
                        <div style="font-weight: 700; color: var(--color-primary);">Calligraphie stylisée active</div>
                        <div style="color: var(--color-text-muted); margin-top: 2px;">Générée automatiquement d'après le nom du signataire.</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulaire d'upload de signature -->
            <form action="/signature/upload" method="POST" enctype="multipart/form-data" id="formUploadSignature">
                <input type="hidden" name="redirect_to" value="/cachet">

                <div class="cachet-dropzone" id="dropzoneSignature" onclick="document.getElementById('sigFileInput').click();" style="padding: 22px 18px;">
                    <input type="file" name="signature_file" id="sigFileInput" accept="image/png,image/jpeg,image/svg+xml,image/webp" style="display: none;" required onchange="handleSigSelected(this);">
                    <div style="font-size: 2.2rem; color: #2563EB; margin-bottom: 8px;">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 4px;">
                        Glissez l'image de la signature manuscrite ici
                    </h4>
                    <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-bottom: 10px;">
                        Format idéal : <strong>PNG avec fond transparent</strong> ou JPG bien contrasté (max 5 Mo)
                    </p>
                    <span class="btn btn-outline" style="display: inline-flex; font-size: 0.8rem; pointer-events: none;">
                        <i class="fa-solid fa-folder-open"></i> Parcourir mes dossiers...
                    </span>
                </div>

                <!-- Aperçu dynamique avant envoi -->
                <div id="liveSigPreview" style="display: none; margin-top: 14px; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px; padding: 12px; align-items: center; gap: 14px;">
                    <img id="tempSigPreviewImg" src="" alt="Aperçu signature" style="height: 50px; max-width: 140px; object-fit: contain;">
                    <div style="flex: 1; font-size: 0.82rem;">
                        <div style="font-weight: 700; color: var(--color-primary);" id="selectedSigFileName">Fichier</div>
                        <div style="color: #059669; font-weight: 600;"><i class="fa-solid fa-check"></i> Prêt à enregistrer</div>
                    </div>
                    <button type="button" class="btn btn-outline" onclick="cancelSigSelection()" style="padding: 4px 10px; font-size: 0.78rem; color: var(--color-danger);">
                        Annuler
                    </button>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 14px;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; gap: 8px;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Enregistrer la Signature Manuscrite
                    </button>
                </div>
            </form>
        </div>

        <!-- CARTE 3 : Importer le Cachet Officiel de l'État Togolais -->
        <div class="data-card" style="padding: 24px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin-bottom: 2px;">
                        <i class="fa-solid fa-stamp" style="color: #059669; margin-right: 8px;"></i> Importer le Cachet / Sceau d'État
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--color-text-muted);">
                        Tampon officiel rond de la Direction Générale du Patrimoine de l'État (Togo).
                    </p>
                </div>
                <div>
                    <?php if ($hasCustomCachet): ?>
                        <span class="badge-official" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; font-weight: 700;">
                            <i class="fa-solid fa-check-circle"></i> Cachet Personnalisé
                        </span>
                    <?php else: ?>
                        <span class="badge-official" style="background-color: #EFF6FF; border-color: #BFDBFE; color: #1D4ED8; font-weight: 700;">
                            <i class="fa-solid fa-shield"></i> Sceau Officiel Défaut
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Aperçu actuel du cachet -->
            <div style="display: flex; align-items: center; gap: 20px; background: #F8FAFC; border: 1px solid var(--color-border); border-radius: 10px; padding: 16px; margin-bottom: 20px;">
                <div class="cachet-preview-box" style="margin: 0; width: 90px; height: 90px;">
                    <img src="<?= htmlspecialchars($activeCachetUrl) ?>" alt="Cachet Actif" id="currentActiveCachetImg">
                </div>
                <div style="flex: 1; font-size: 0.82rem;">
                    <div style="font-weight: 700; color: var(--color-primary);"><?= htmlspecialchars($cachetInfo['filename']) ?> (<?= htmlspecialchars($cachetInfo['filesize']) ?>)</div>
                    <div style="color: var(--color-text-muted); margin-top: 2px;">Dernière mise à jour : <?= htmlspecialchars($cachetInfo['updated_at']) ?></div>
                    <?php if ($hasCustomCachet): ?>
                        <form action="/cachet/reset" method="POST" style="margin-top: 8px;" onsubmit="return confirm('Rétablir le sceau standard officiel de la République Togolaise ?');">
                            <input type="hidden" name="redirect_to" value="/cachet">
                            <button type="submit" class="btn btn-outline" style="color: #B91C1C; border-color: #FECACA; padding: 4px 10px; font-size: 0.78rem;">
                                <i class="fa-solid fa-rotate-left"></i> Rétablir le Sceau Officiel par Défaut
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Formulaire d'upload de cachet -->
            <form action="/cachet/upload" method="POST" enctype="multipart/form-data" id="formUploadCachet">
                <input type="hidden" name="redirect_to" value="/cachet">

                <div class="cachet-dropzone" id="dropzoneCachet" onclick="document.getElementById('cachetFileInput').click();" style="padding: 22px 18px;">
                    <input type="file" name="cachet_file" id="cachetFileInput" accept="image/png,image/jpeg,image/svg+xml,image/webp" style="display: none;" required onchange="handleCachetSelected(this);">
                    <div style="font-size: 2.2rem; color: #059669; margin-bottom: 8px;">
                        <i class="fa-solid fa-stamp"></i>
                    </div>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: var(--color-primary); margin-bottom: 4px;">
                        Glissez le timbre ou cachet officiel ici
                    </h4>
                    <p style="font-size: 0.78rem; color: var(--color-text-muted); margin-bottom: 10px;">
                        Formats recommandés : <strong>PNG avec fond transparent</strong> ou SVG vectoriel
                    </p>
                    <span class="btn btn-outline" style="display: inline-flex; font-size: 0.8rem; pointer-events: none;">
                        <i class="fa-solid fa-folder-open"></i> Parcourir mes dossiers...
                    </span>
                </div>

                <!-- Aperçu dynamique avant envoi -->
                <div id="liveCachetPreview" style="display: none; margin-top: 14px; background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 8px; padding: 12px; align-items: center; gap: 14px;">
                    <img id="tempCachetPreviewImg" src="" alt="Aperçu cachet" style="height: 60px; width: 60px; object-fit: contain;">
                    <div style="flex: 1; font-size: 0.82rem;">
                        <div style="font-weight: 700; color: var(--color-primary);" id="selectedCachetFileName">Fichier</div>
                        <div style="color: #059669; font-weight: 600;"><i class="fa-solid fa-check"></i> Prêt pour déploiement officiel</div>
                    </div>
                    <button type="button" class="btn btn-outline" onclick="cancelCachetSelection()" style="padding: 4px 10px; font-size: 0.78rem; color: var(--color-danger);">
                        Annuler
                    </button>
                </div>

                <div style="display: flex; justify-content: flex-end; margin-top: 14px;">
                    <button type="submit" class="btn btn-primary" style="font-weight: 700; gap: 8px;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Enregistrer et Déployer le Cachet
                    </button>
                </div>
            </form>
        </div>

    </div>

    <!-- Colonne DROITE : Simulateur en Direct sur Acte Administratif Officiel -->
    <div>
        <div class="data-card" style="padding: 24px; position: sticky; top: 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; border-bottom: 1px solid var(--color-border); padding-bottom: 12px;">
                <div>
                    <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-primary); margin-bottom: 2px;">
                        <i class="fa-solid fa-file-contract" style="color: var(--color-accent); margin-right: 8px;"></i> Rendu Officiel en Situation Réelle
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--color-text-muted);">
                        Visualisation dynamique du bloc de signature certifié conforme (Togo)
                    </p>
                </div>
                <span class="badge-official" style="font-size: 0.75rem;">
                    Spécimen N° TG-<?= date('Y') ?>-CERT
                </span>
            </div>

            <!-- Fiche Document Administratif Réaliste -->
            <div class="doc-mockup-sheet" style="font-size: 0.86rem; line-height: 1.6; color: #1E293B;">
                
                <!-- En-tête officiel Togo -->
                <div class="doc-mockup-header">
                    <div>
                        <div style="font-size: 0.85rem; font-weight: 800; color: #0D3B66; letter-spacing: 0.8px;">RÉPUBLIQUE TOGOLAISE</div>
                        <div style="font-size: 0.72rem; font-weight: 700; color: #059669;">Travail - Liberté - Patrie</div>
                        <div style="margin-top: 8px; font-size: 0.78rem; font-weight: 700; color: #334155;">MINISTÈRE DE L'ÉCONOMIE ET DES FINANCES</div>
                        <div style="font-size: 0.74rem; color: #64748B;">DIRECTION GÉNÉRALE DU PATRIMOINE DE L'ÉTAT</div>
                    </div>
                    <div style="text-align: right; font-size: 0.78rem; color: #64748B;">
                        <div>B.P. 387, Lomé - TOGO</div>
                        <div>Tél : +228 22 21 21 00</div>
                        <div style="margin-top: 6px; font-weight: 700; color: #0D3B66;" id="previewDocDate">Fait à <?= htmlspecialchars($signataireSettings['lieu']) ?>, le <?= date('d/m/Y') ?></div>
                    </div>
                </div>

                <!-- Titre de l'acte -->
                <div style="text-align: center; margin: 18px 0 20px 0;">
                    <div style="font-size: 1.15rem; font-weight: 800; color: #0D3B66; text-transform: uppercase; letter-spacing: 0.5px;">
                        BORDEREAU OFFICIEL D'INVENTAIRE ET DE VALORISATION
                    </div>
                    <div style="font-size: 0.78rem; color: #64748B; font-weight: 600;">
                        Réf. DGPE/TG/<?= date('Y') ?>/PV-0082 &bull; Enregistrement au Registre National
                    </div>
                </div>

                <!-- Corps de texte -->
                <p style="margin-bottom: 12px;">
                    Le présent document certifie que les biens inscrits au registre national du patrimoine de la <strong>République Togolaise</strong> ont été inventoriés et certifiés conformes.
                </p>

                <div style="background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 6px; padding: 12px 14px; margin-bottom: 20px; font-size: 0.8rem;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span><strong>Total des immobilisations recensées :</strong></span>
                        <span style="font-weight: 700; color: #0D3B66;">4 Biens d'État inscrits</span>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span><strong>Statut de conformité légale :</strong></span>
                        <span style="font-weight: 700; color: #059669;"><i class="fa-solid fa-circle-check"></i> Régulier et Inaliénable</span>
                    </div>
                </div>

                <!-- Section Signature & Cachet Apposé -->
                <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 26px; padding-top: 10px;">
                    <div style="font-size: 0.78rem; color: #64748B;">
                        <div>Mentions d'enregistrement :</div>
                        <div style="font-family: monospace; font-size: 0.85rem; color: #0D3B66; font-weight: 700;">TG-MEF-DGPE-<?= date('Ymd') ?>-OK</div>
                        <div style="margin-top: 4px; font-size: 0.72rem; color: #94A3B8;">Document officiel certifié sans rature</div>
                    </div>

                    <div style="text-align: right; min-width: 250px; position: relative;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: #0D3B66;" id="previewDocFormule">
                            <?= htmlspecialchars($signataireSettings['formule']) ?>
                        </div>
                        <div style="font-size: 0.85rem; font-weight: 800; color: #0D3B66; margin-top: 2px;" id="previewDocTitre">
                            <?= htmlspecialchars($signataireSettings['titre']) ?>
                        </div>

                        <!-- NOM DU SIGNATAIRE AU-DESSUS DE LA SIGNATURE (DYNAMIQUE) -->
                        <div style="font-size: 0.95rem; font-weight: 800; color: #0D3B66; margin-top: 5px; letter-spacing: 0.3px;" id="previewDocNom">
                            <?= htmlspecialchars($signataireSettings['nom']) ?>
                        </div>

                        <!-- Zone Signature et Superposition du Cachet -->
                        <div style="height: 75px; display: flex; align-items: center; justify-content: flex-end; position: relative; margin: 4px 0;">
                            <!-- Empreinte de la Signature (Image ou Calligraphie) -->
                            <div id="previewSigContainer" style="z-index: 1;">
                                <?php if (!empty($signatureInfo['is_custom'])): ?>
                                    <img src="<?= htmlspecialchars($signatureInfo['url']) ?>" id="docMockupSig" alt="Signature" style="max-height: 60px; max-width: 170px; object-fit: contain; transform: rotate(-3deg);">
                                <?php else: ?>
                                    <div id="docMockupCalligraphy" style="font-family: 'Brush Script MT', cursive, serif; font-size: 2.1rem; color: #1E3A8A; transform: rotate(-5deg); z-index: 1;">
                                        <?= htmlspecialchars($signataireSettings['nom']) ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Empreinte du Cachet Officiel superposée -->
                            <img src="<?= htmlspecialchars($activeCachetUrl) ?>" id="docMockupStamp" alt="Cachet d'État" style="position: absolute; right: 0; width: 110px; height: 110px; object-fit: contain; transform: rotate(-7deg); opacity: 0.88; filter: drop-shadow(0 2px 5px rgba(13, 59, 102, 0.25)); pointer-events: none; z-index: 2;">
                        </div>

                        <div style="font-size: 0.76rem; font-weight: 700; color: #334155; border-top: 1px solid #CBD5E1; padding-top: 4px; width: 90%; margin-left: auto;">
                            Direction Générale du Patrimoine &bull; Togo
                        </div>
                    </div>
                </div>

            </div>

            <!-- Lien rapide vers les Rapports -->
            <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; background: #F1F5F9; border-radius: 8px; padding: 12px 18px;">
                <div style="font-size: 0.82rem; color: #475569;">
                    <i class="fa-solid fa-circle-check" style="color: #059669; margin-right: 6px;"></i>
                    Le nom, la signature et le cachet sont immédiatement répercutés sur les <strong>Rapports Globaux</strong>.
                </div>
                <a href="/rapports" class="btn btn-outline" style="padding: 6px 14px; font-size: 0.82rem;">
                    Ouvrir les Rapports <i class="fa-solid fa-arrow-right" style="margin-left: 4px;"></i>
                </a>
            </div>

        </div>
    </div>

</div>

<!-- Scripts interactifs pour synchronisation en direct -->
<script>
// Mise à jour instantanée du simulateur quand on tape dans les champs du formulaire
function updateLiveSimulator() {
    const nom = document.getElementById('inputSignataireNom').value || 'Amele Rolande GOLI';
    const titre = document.getElementById('inputSignataireTitre').value || 'Le Directeur Général du Patrimoine';
    const formule = document.getElementById('inputSignataireFormule').value || 'Pour le Ministre et par ordre,';
    const lieu = document.getElementById('inputSignataireLieu').value || 'Lomé';

    document.getElementById('previewDocNom').innerText = nom;
    document.getElementById('previewDocTitre').innerText = titre;
    document.getElementById('previewDocFormule').innerText = formule;
    document.getElementById('previewDocDate').innerText = 'Fait à ' + lieu + ', le <?= date('d/m/Y') ?>';

    const calligraphy = document.getElementById('docMockupCalligraphy');
    if (calligraphy) {
        calligraphy.innerText = nom;
    }
}

// Gestion de la sélection d'une image de signature
function handleSigSelected(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('selectedSigFileName').innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' Ko)';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('tempSigPreviewImg').src = e.target.result;
            document.getElementById('liveSigPreview').style.display = 'flex';
            
            // Mettre à jour l'aperçu du document immédiatement
            const container = document.getElementById('previewSigContainer');
            container.innerHTML = '<img src="' + e.target.result + '" style="max-height: 60px; max-width: 170px; object-fit: contain; transform: rotate(-3deg);">';
        };
        reader.readAsDataURL(file);
    }
}

function cancelSigSelection() {
    const input = document.getElementById('sigFileInput');
    input.value = '';
    document.getElementById('liveSigPreview').style.display = 'none';
    window.location.reload();
}

// Gestion de la sélection du cachet
function handleCachetSelected(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        document.getElementById('selectedCachetFileName').innerText = file.name + ' (' + (file.size / 1024).toFixed(1) + ' Ko)';
        
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('tempCachetPreviewImg').src = e.target.result;
            document.getElementById('liveCachetPreview').style.display = 'flex';
            
            // Mettre à jour l'aperçu du cachet sur le document immédiatement
            const stamp = document.getElementById('docMockupStamp');
            if (stamp) stamp.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function cancelCachetSelection() {
    const input = document.getElementById('cachetFileInput');
    input.value = '';
    document.getElementById('liveCachetPreview').style.display = 'none';
    const stamp = document.getElementById('docMockupStamp');
    const currentActive = document.getElementById('currentActiveCachetImg');
    if (stamp && currentActive) {
        stamp.src = currentActive.src;
    }
}

// Glisser-déposer pour Signature
const dropSig = document.getElementById('dropzoneSignature');
if (dropSig) {
    ['dragenter', 'dragover'].forEach(eventName => {
        dropSig.addEventListener(eventName, (e) => { e.preventDefault(); dropSig.classList.add('dragover'); }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropSig.addEventListener(eventName, (e) => { e.preventDefault(); dropSig.classList.remove('dragover'); }, false);
    });
    dropSig.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const input = document.getElementById('sigFileInput');
            input.files = files;
            handleSigSelected(input);
        }
    });
}

// Glisser-déposer pour Cachet
const dropCachet = document.getElementById('dropzoneCachet');
if (dropCachet) {
    ['dragenter', 'dragover'].forEach(eventName => {
        dropCachet.addEventListener(eventName, (e) => { e.preventDefault(); dropCachet.classList.add('dragover'); }, false);
    });
    ['dragleave', 'drop'].forEach(eventName => {
        dropCachet.addEventListener(eventName, (e) => { e.preventDefault(); dropCachet.classList.remove('dragover'); }, false);
    });
    dropCachet.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const input = document.getElementById('cachetFileInput');
            input.files = files;
            handleCachetSelected(input);
        }
    });
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
