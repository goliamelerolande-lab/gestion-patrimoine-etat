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
    $pageTitle = 'Mon Espace Agent de l\'État (Togo)';
    $pageSubtitle = 'Suivi de vos équipements de fonction, récépissés de décharge et signalement d\'incidents';
} elseif ($roleKey === 'gestionnaire') {
    $pageTitle = 'Tableau de Bord du Gestionnaire de Parc (Togo)';
    $pageSubtitle = 'Supervision logistique, taux de disponibilité des matériels et suivi des interventions';
} else {
    $pageTitle = 'Tableau de Bord Exécutif (Togo)';
    $pageSubtitle = 'Aperçu synthétique et valorisation du parc mobilier et immobilier de l\'État Togolais';
}
$currentPage = 'dashboard';
ob_start();
?>

<?php if ($roleKey === 'agent'): ?>
    <!-- ===================================================================== -->
    <!-- 1. VUE PERSONNALISÉE : AGENT PUBLIC BÉNÉFICIAIRE                       -->
    <!-- ===================================================================== -->

    <!-- Carte d'identité administrative de l'agent -->
    <div class="data-card" style="margin-bottom: 24px; padding: 24px; background: linear-gradient(135deg, #FFFFFF 0%, #F0FDF4 100%); border-left: 5px solid #059669;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 18px;">
            <div style="display: flex; align-items: center; gap: 18px;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #059669, #10B981); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; font-weight: 800; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.25);">
                    <?= htmlspecialchars(substr($currentUser['name'] ?? 'AM', 0, 2)) ?>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                        <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--color-primary); margin: 0;">
                            <?= htmlspecialchars($currentUser['name'] ?? 'Afiwa MENSAH') ?>
                        </h2>
                        <span style="font-size: 0.75rem; font-weight: 700; background-color: #ECFDF5; color: #059669; border: 1px solid #A7F3D0; padding: 3px 10px; border-radius: 9999px;">
                            <i class="fa-solid fa-circle-check"></i> Agent Public Habilité
                        </span>
                    </div>
                    <div style="font-size: 0.88rem; color: var(--color-text-muted); margin-top: 4px;">
                        <strong>Matricule :</strong> <?= htmlspecialchars($currentUser['matricule'] ?? 'MAT-TG-003') ?>
                        &bull; <strong>Service :</strong> <?= htmlspecialchars($currentUser['service'] ?? 'Direction de la Transformation Digitale') ?>
                        &bull; <strong>Ministère :</strong> <?= htmlspecialchars($currentUser['ministere_nom'] ?? 'Ministère de l\'Économie Numérique (MENTD)') ?>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <a href="/maintenances" class="btn btn-primary" style="background-color: #DC2626; border-color: #DC2626;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Signaler une Panne</span>
                </a>
                <button type="button" class="btn btn-outline" onclick="openGlobalProfileModal();">
                    <i class="fa-solid fa-user-pen"></i>
                    <span>Mon Compte</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3 Cartes Métriques Spécifiques Agent -->
    <?php
        $nbAgentIncidents = !empty($maintenances) ? count(array_filter($maintenances, function($m) {
            return ($m['code_inventaire'] ?? '') === 'GPE-000015' || ($m['statut'] ?? '') === 'En attente';
        })) : 0;
    ?>
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Équipements Confiés</span>
                <div class="metric-icon-box" style="background-color: #ECFDF5; color: #059669;">
                    <i class="fa-solid fa-laptop-file"></i>
                </div>
            </div>
            <div class="metric-val">1</div>
            <div class="metric-footer">
                <i class="fa-solid fa-check" style="color: #059669;"></i> Sous votre entière responsabilité
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Signalements d'Incidents</span>
                <div class="metric-icon-box" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>
            <div class="metric-val"><?= $nbAgentIncidents ?></div>
            <div class="metric-footer">
                <?php if ($nbAgentIncidents > 0): ?>
                    <i class="fa-solid fa-triangle-exclamation" style="color: #D97706;"></i> En cours de prise en charge DGPE
                <?php else: ?>
                    <i class="fa-solid fa-circle-check" style="color: #059669;"></i> Aucun problème déclaré
                <?php endif; ?>
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Décharges & Dotations</span>
                <div class="metric-icon-box" style="background-color: #EFF6FF; color: #2563EB;">
                    <i class="fa-solid fa-file-contract"></i>
                </div>
            </div>
            <div class="metric-val">1</div>
            <div class="metric-footer">
                <i class="fa-solid fa-stamp" style="color: #2563EB;"></i> Récépissé d'affectation certifié
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">État Général du Matériel</span>
                <div class="metric-icon-box" style="background-color: #F5F3FF; color: #7C3AED;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
            <div class="metric-val" style="font-size: 1.35rem; color: #059669;">100% Fonctionnel</div>
            <div class="metric-footer">
                <i class="fa-solid fa-circle" style="color: #059669;"></i> En service au Data Centre
            </div>
        </div>
    </div>

    <!-- Tableau : Matériel confié à l'Agent -->
    <div class="data-card" style="margin-top: 24px;">
        <div class="card-header-bar">
            <h3 class="card-header-title">
                <i class="fa-solid fa-box-archive" style="color: #059669; margin-right: 8px;"></i>
                Matériel Confié sous Décharge Officielle
            </h3>
            <a href="/affectations" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-file-pdf"></i>
                <span>Consulter ma Décharge</span>
            </a>
        </div>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Code Inventaire</th>
                        <th>Désignation de l'Équipement</th>
                        <th>Date de Prise en Charge</th>
                        <th>Localisation</th>
                        <th>État d'Usage</th>
                        <th style="text-align: center;">Assistance</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="code-badge">GPE-000015</span></td>
                        <td>
                            <div style="font-weight: 700; color: var(--color-primary);">Serveur Rack Dell PowerEdge R750 64GB</div>
                            <small style="color: var(--color-text-muted);">Matériels Informatiques & Télécoms (MENTD)</small>
                        </td>
                        <td>25/02/2026</td>
                        <td><i class="fa-solid fa-location-dot" style="color: var(--color-text-muted);"></i> Lomé Data Centre - Salle Serveurs</td>
                        <td><span class="status-pill status-bon">● Bon état</span></td>
                        <td style="text-align: center;">
                            <a href="/maintenances" class="btn btn-outline btn-sm" style="color: #DC2626; border-color: #FCA5A5;">
                                <i class="fa-solid fa-triangle-exclamation"></i> Signaler une Panne
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Charte de Déontologie de l'Agent Public Togolais -->
    <div class="data-card" style="margin-top: 24px; background-color: #F8FAFC; border: 1px dashed var(--color-border); padding: 22px;">
        <h4 style="color: var(--color-primary); font-size: 1rem; font-weight: 800; margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
            <i class="fa-solid fa-scale-balanced" style="color: #059669;"></i>
            Rappel des Directives & Déontologie de l'Agent de l'État (Togo)
        </h4>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 14px; font-size: 0.83rem; color: var(--color-text);">
            <div style="background: #FFF; padding: 12px 14px; border-radius: var(--radius-md); border-left: 3px solid #059669;">
                <strong>1. Conservation du Bien :</strong> L'agent bénéficiaire veille à l'entretien courant et à l'intégrité du matériel confié par l'administration.
            </div>
            <div style="background: #FFF; padding: 12px 14px; border-radius: var(--radius-md); border-left: 3px solid #2563EB;">
                <strong>2. Usage Professionnel :</strong> Le matériel de l'État Togolais est exclusivement dévolu à l'accomplissement des missions de service public.
            </div>
            <div style="background: #FFF; padding: 12px 14px; border-radius: var(--radius-md); border-left: 3px solid #D97706;">
                <strong>3. Signalement Obligatoire :</strong> Tout dysfonctionnement, panne ou dommage doit être immédiatement déclaré via la plateforme.
            </div>
            <div style="background: #FFF; padding: 12px 14px; border-radius: var(--radius-md); border-left: 3px solid #DC2626;">
                <strong>4. Restitution Formelle :</strong> En cas de mutation, congé prolongé ou fin de mission, le bien doit être formellement restitué avec décharge.
            </div>
        </div>
    </div>

<?php elseif ($roleKey === 'gestionnaire'): ?>
    <!-- ===================================================================== -->
    <!-- 2. VUE PERSONNALISÉE : GESTIONNAIRE DU PATRIMOINE (MEF)                 -->
    <!-- ===================================================================== -->
    <?php
        $totalBiensCount = !empty($biens) ? count($biens) : (int)($stats['total_biens'] ?? 4);
        $totalAffCount = count($affectations ?? []);
        $totalMaintCount = count($maintenances ?? []);
        // Le bien en réparation fait partie du parc existant (déjà compté dans les affectations ou le stock)
        $nbEnPanne = min($totalMaintCount, $totalBiensCount);
        // Biens affectés 100% opérationnels (sans panne)
        $nbEnServiceOp = max(0, $totalAffCount - $nbEnPanne);
        // Biens disponibles en stock (non affectés)
        $nbEnStock = max(0, $totalBiensCount - $totalAffCount);
        // Taux de disponibilité opérationnelle réelle (3 opérationnels / 4 au total = 75%)
        $tauxDispo = $totalBiensCount > 0 ? round((($totalBiensCount - $nbEnPanne) / $totalBiensCount) * 100) : 100;
    ?>

    <!-- Raccourcis Rapides de Gestion Opérationnelle -->
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; margin-bottom: 20px;">
        <div style="font-size: 0.9rem; color: var(--color-text-muted);">
            <i class="fa-solid fa-user-tie" style="color: #2563EB;"></i> Gestionnaire de Parc : <strong><?= htmlspecialchars($currentUser['name'] ?? 'Koffi AGBEKO') ?></strong> (MEF)
        </div>
        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="/biens" class="btn btn-outline btn-sm">
                <i class="fa-solid fa-plus"></i> Nouveau Bien
            </a>
            <a href="/affectations" class="btn btn-primary btn-sm" style="background-color: #2563EB; border-color: #2563EB;">
                <i class="fa-solid fa-people-carry-box"></i> Nouvelle Affectation
            </a>
            <a href="/maintenances" class="btn btn-outline btn-sm" style="color: #DC2626; border-color: #FCA5A5;">
                <i class="fa-solid fa-triangle-exclamation"></i> Suivi des Pannes
            </a>
        </div>
    </div>

    <!-- 4 Cartes Métriques Opérationnelles Gestionnaire (Cohérence stricte 4 Biens au total) -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Biens en Service Actif</span>
                <div class="metric-icon-box" style="background-color: #EFF6FF; color: #2563EB;">
                    <i class="fa-solid fa-people-carry-box"></i>
                </div>
            </div>
            <div class="metric-val"><?= $totalAffCount ?></div>
            <div class="metric-footer">
                <i class="fa-solid fa-arrow-right" style="color: #2563EB;"></i> Confiés aux fonctionnaires
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Biens Disponibles en Stock</span>
                <div class="metric-icon-box" style="background-color: #ECFDF5; color: #059669;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="metric-val"><?= $nbEnStock ?></div>
            <div class="metric-footer">
                <i class="fa-solid fa-circle-check" style="color: #059669;"></i> Prêts à être affectés
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Pannes sous Réparation</span>
                <div class="metric-icon-box" style="background-color: #FEE2E2; color: #DC2626;">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
            </div>
            <div class="metric-val"><?= $nbEnPanne ?></div>
            <div class="metric-footer">
                <i class="fa-solid fa-triangle-exclamation" style="color: #DC2626;"></i> Suivi des prestataires agréés
            </div>
        </div>

        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Disponibilité du Parc</span>
                <div class="metric-icon-box" style="background-color: #FEF3C7; color: #D97706;">
                    <i class="fa-solid fa-chart-pie"></i>
                </div>
            </div>
            <div class="metric-val" style="color: #059669;"><?= $tauxDispo ?> %</div>
            <div class="metric-footer">
                <i class="fa-solid fa-check"></i> <?= ($totalBiensCount - $nbEnPanne) ?> sur <?= $totalBiensCount ?> biens opérationnels
            </div>
        </div>
    </div>

    <!-- Graphiques Opérationnels Gestionnaire -->
    <div class="charts-grid">
        <div class="chart-card">
            <h3><i class="fa-solid fa-chart-pie" style="color: #2563EB;"></i> Disponibilité Opérationnelle du Parc</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="dispoChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h3><i class="fa-solid fa-chart-bar" style="color: #0D3B66;"></i> Répartition du Matériel par Catégorie</h3>
            <div style="height: 250px; position: relative;">
                <canvas id="categorieChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Derniers Mouvements d'Affectation -->
    <div class="data-card" style="margin-top: 24px;">
        <div class="card-header-bar">
            <h3 class="card-header-title">Dernières Décharges & Mouvements de Matériel</h3>
            <a href="/affectations" class="btn btn-outline btn-sm">
                <span>Gérer les affectations</span>
                <i class="fa-solid fa-arrow-right"></i>
            </a>
        </div>
        <div class="table-responsive">
            <table class="app-table">
                <thead>
                    <tr>
                        <th>Code Bien</th>
                        <th>Agent Bénéficiaire</th>
                        <th>Ministère / Service</th>
                        <th>Date d'Affectation</th>
                        <th>Statut</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_slice($affectations ?? [], 0, 4) as $aff): ?>
                    <tr>
                        <td><span class="code-badge"><?= htmlspecialchars($aff['code_inventaire']) ?></span></td>
                        <td><strong><?= htmlspecialchars($aff['beneficiaire_nom']) ?></strong></td>
                        <td><?= htmlspecialchars($aff['service_nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($aff['date_affectation'])) ?></td>
                        <td><span class="status-pill status-bon">● <?= htmlspecialchars($aff['statut']) ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<?php else: ?>
    <!-- ===================================================================== -->
    <!-- 3. VUE EXÉCUTIVE DGPE : SUPER-ADMINISTRATEUR (Amele Rolande GOLI)       -->
    <!-- ===================================================================== -->

    <!-- Cartes Métriques Nationales -->
    <div class="metrics-grid">
        <div class="metric-card">
            <div class="metric-header">
                <span class="metric-label">Total des Biens Nationaux</span>
                <div class="metric-icon-box" style="background-color: #EFF6FF; color: #2563EB;">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="metric-val"><?= number_format($stats['total_biens'] ?? 0, 0, ',', ' ') ?></div>
            <div class="metric-footer">
                <i class="fa-solid fa-circle-check" style="color: var(--color-success);"></i> Registre certifié DGPE
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
                <i class="fa-solid fa-calendar"></i> Actualisation : <?= date('d/m/Y') ?>
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

    <!-- Graphiques Exécutifs Chart.js -->
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

    <!-- Derniers Biens Enregistrés au Registre National -->
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
<?php endif; ?>

<!-- Scripts des Graphiques Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    <?php if ($roleKey === 'superadmin'): ?>
        // Données pour le Graphique par État
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
                plugins: { legend: { position: 'bottom' } }
            }
        });
    <?php endif; ?>

    <?php if ($roleKey === 'gestionnaire'): ?>
        // Données pour la Disponibilité du Parc (Total = <?= $totalBiensCount ?> biens)
        new Chart(document.getElementById('dispoChart'), {
            type: 'doughnut',
            data: {
                labels: ['En Service Opérationnel', 'Disponible en Stock', 'En Réparation'],
                datasets: [{
                    data: [<?= $nbEnServiceOp ?>, <?= $nbEnStock ?>, <?= $nbEnPanne ?>],
                    backgroundColor: ['#2563EB', '#10B981', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    <?php endif; ?>

    <?php if ($roleKey === 'superadmin' || $roleKey === 'gestionnaire'): ?>
        // Données pour le Graphique par Catégorie
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
                scales: { y: { beginAtZero: true } }
            }
        });
    <?php endif; ?>
});
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
