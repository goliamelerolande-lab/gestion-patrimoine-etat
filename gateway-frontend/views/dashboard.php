<?php
$pageTitle = 'Tableau de Bord Exécutif (Togo)';
$pageSubtitle = 'Aperçu synthétique et valorisation du parc mobilier et immobilier de l\'État Togolais';
$currentPage = 'dashboard';
ob_start();
?>

<!-- 1. Cartes Métriques KPI -->
<div class="metrics-grid">
    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-label">Total des Biens</span>
            <div class="metric-icon-box" style="background-color: #EFF6FF; color: #2563EB;">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>
        <div class="metric-val"><?= number_format($stats['total_biens'] ?? 0, 0, ',', ' ') ?></div>
        <div class="metric-footer">
            <i class="fa-solid fa-circle-check" style="color: var(--color-success);"></i> Registre certifié
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-label">Valeur Cumulée du Patrimoine</span>
            <div class="metric-icon-box" style="background-color: #ECFDF5; color: #059669;">
                <i class="fa-solid fa-money-bill-wave"></i>
            </div>
        </div>
        <div class="metric-val" style="color: #059669; font-size: 1.55rem;">
            <?= number_format($stats['valeur_cumulee_fcfa'] ?? 0, 0, ',', ' ') ?> <small style="font-size: 0.9rem;">FCFA</small>
        </div>
        <div class="metric-footer">
            <i class="fa-solid fa-calendar"></i> Date d'actualisation : <?= date('d/m/Y') ?>
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-label">Affectations Actives</span>
            <div class="metric-icon-box" style="background-color: #FEF3C7; color: #D97706;">
                <i class="fa-solid fa-users"></i>
            </div>
        </div>
        <div class="metric-val"><?= count($affectations ?? []) ?></div>
        <div class="metric-footer">
            <i class="fa-solid fa-arrow-right"></i> En service dans les ministères
        </div>
    </div>

    <div class="metric-card">
        <div class="metric-header">
            <span class="metric-label">Tickets de Maintenance</span>
            <div class="metric-icon-box" style="background-color: #FEE2E2; color: #DC2626;">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
        </div>
        <div class="metric-val"><?= count($maintenances ?? []) ?></div>
        <div class="metric-footer">
            <i class="fa-solid fa-triangle-exclamation" style="color: var(--color-danger);"></i> Suivi des réparations
        </div>
    </div>
</div>

<!-- 2. Graphiques de Visualisation Chart.js -->
<div class="charts-grid">
    <div class="chart-card">
        <h3><i class="fa-solid fa-chart-pie"></i> Répartition du Patrimoine par État d'Usage</h3>
        <div style="height: 260px; position: relative;">
            <canvas id="etatChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3><i class="fa-solid fa-chart-bar"></i> Valeur d'Acquisition par Catégorie (FCFA)</h3>
        <div style="height: 260px; position: relative;">
            <canvas id="categorieChart"></canvas>
        </div>
    </div>
</div>

<!-- 3. Derniers Biens Enregistrés -->
<div class="data-card">
    <div class="card-header-bar">
        <h3 class="card-header-title">Derniers Biens Enregistrés au Registre National (Togo)</h3>
        <a href="/biens" class="btn btn-outline btn-sm">
            <span>Voir tout l'inventaire</span>
            <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Code Inventaire</th>
                    <th>Libellé du Bien</th>
                    <th>Date d'Acquisition</th>
                    <th>Valeur (FCFA)</th>
                    <th>État</th>
                    <th>Localisation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach (array_slice($biens, 0, 5) as $bien): ?>
                <tr>
                    <td><span class="code-badge"><?= htmlspecialchars($bien['code_inventaire']) ?></span></td>
                    <td><strong><?= htmlspecialchars($bien['libelle']) ?></strong></td>
                    <td><?= date('d/m/Y', strtotime($bien['date_acquisition'])) ?></td>
                    <td class="price-tag"><?= number_format($bien['valeur_acquisition_fcfa'], 0, ',', ' ') ?> FCFA</td>
                    <td>
                        <?php
                            $etatClass = 'status-neuf';
                            if ($bien['etat'] === 'Bon état') $etatClass = 'status-bon';
                            if ($bien['etat'] === 'En panne') $etatClass = 'status-panne';
                            if ($bien['etat'] === 'Passable') $etatClass = 'status-passable';
                        ?>
                        <span class="status-pill <?= $etatClass ?>"><?= htmlspecialchars($bien['etat']) ?></span>
                    </td>
                    <td><i class="fa-solid fa-location-dot" style="color: var(--color-text-muted);"></i> <?= htmlspecialchars($bien['localisation']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts des Graphiques Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Données pour le Graphique par État
    const etatLabels = <?= json_encode(array_column($stats['repartition_etat'] ?? [], 'etat')) ?>;
    const etatData   = <?= json_encode(array_column($stats['repartition_etat'] ?? [], 'count')) ?>;
    
    new Chart(document.getElementById('etatChart'), {
        type: 'doughnut',
        data: {
            labels: etatLabels.length ? etatLabels : ['Neuf', 'Bon état', 'En panne'],
            datasets: [{
                data: etatData.length ? etatData : [1, 3, 1],
                backgroundColor: ['#10B981', '#3B82F6', '#EF4444', '#F59E0B']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // 2. Données pour le Graphique par Catégorie
    const catLabels = <?= json_encode(array_column($stats['repartition_categorie'] ?? [], 'libelle')) ?>;
    const catData   = <?= json_encode(array_column($stats['repartition_categorie'] ?? [], 'valeur')) ?>;

    new Chart(document.getElementById('categorieChart'), {
        type: 'bar',
        data: {
            labels: catLabels.length ? catLabels : ['Véhicules', 'Bâtiments', 'Informatique', 'Mobilier'],
            datasets: [{
                label: 'Valeur (FCFA)',
                data: catData.length ? catData : [26500000, 850000000, 12100000, 120000],
                backgroundColor: '#0D3B66'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
