<?php
$pageTitle = 'Rapports Globaux';
$pageSubtitle = 'Analyse détaillée et exportation des données du patrimoine national';
$currentPage = 'rapports';
ob_start();
?>

<!-- Bouton d'exportation / impression officiel -->
<div style="display: flex; justify-content: flex-end; margin-bottom: 24px;" class="no-print">
    <button class="btn btn-primary" onclick="window.print();">
        <i class="fa-solid fa-print"></i>
        <span>Imprimer / Exporter en PDF</span>
    </button>
</div>

<!-- Bloc officiel de rapport (format fidèle à Rapports - GPE.pdf) -->
<div class="report-container">
    <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 24px; border-bottom: 2px solid var(--color-primary); padding-bottom: 12px;">
        <div>
            <h1 style="font-size: 2rem; font-weight: 800; color: var(--color-primary); margin-bottom: 4px;">Rapports Globaux</h1>
            <p style="color: var(--color-text-muted); font-size: 1.05rem;">Analyse détaillée et exportation des données</p>
        </div>
        <div style="text-align: right; font-size: 0.85rem; color: var(--color-text-muted);">
            <div>Direction Générale du Patrimoine de l'État</div>
            <strong>Rapports - GPE</strong>
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
        <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--color-primary); margin-bottom: 4px;">Répartition globale par état</h3>
        <p style="font-size: 0.85rem; color: var(--color-text-muted); margin-bottom: 20px;">Totaux calculés sur l’ensemble du registre.</p>

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

    <!-- 3. Tableau des 20 biens les plus récents -->
    <div style="background: #FFFFFF; border-radius: 14px; box-shadow: var(--shadow-card); border: 1px solid var(--color-border); overflow: hidden;">
        <div style="padding: 20px 24px; border-bottom: 1px solid var(--color-border);">
            <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--color-primary);">Derniers biens enregistrés</h3>
            <p style="font-size: 0.85rem; color: var(--color-text-muted);">Les 20 biens les plus récents du registre</p>
        </div>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th style="font-size: 0.8rem;">CODE INVENTAIRE</th>
                        <th style="font-size: 0.8rem;">LIBELLÉ</th>
                        <th style="font-size: 0.8rem;">DATE D'ACQUISITION</th>
                        <th style="font-size: 0.8rem;">VALEUR</th>
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
                        </td>
                        <td style="font-size: 0.95rem;"><?= date('d/m/Y', strtotime($b['date_acquisition'])) ?></td>
                        <td>
                            <span style="font-weight: 800; font-size: 1.1rem; color: #059669;">
                                <?= number_format($b['valeur_acquisition_fcfa'], 0, ',', ' ') ?> F
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
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
