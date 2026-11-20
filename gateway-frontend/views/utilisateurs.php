<?php
$pageTitle = 'Annuaire des Agents & Ministères (Togo)';
$pageSubtitle = 'Gestion des comptes administratifs, privilèges et rattachements ministériels de l\'État Togolais';
$currentPage = 'utilisateurs';
ob_start();
?>

<div class="data-card">
    <div class="card-header-bar">
        <h3 class="card-header-title">Utilisateurs et Agents Habilités - Togo (<?= count($users) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Matricule</th>
                    <th>Nom & Prénoms</th>
                    <th>Email Institutionnel</th>
                    <th>Rôle</th>
                    <th>Ministère / Direction</th>
                    <th>Téléphone</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $u): ?>
                <tr>
                    <td><span class="code-badge"><?= htmlspecialchars($u['matricule']) ?></span></td>
                    <td><strong><?= htmlspecialchars($u['name']) ?></strong></td>
                    <td><i class="fa-regular fa-envelope"></i> <?= htmlspecialchars($u['email']) ?></td>
                    <td>
                        <?php
                            $roleBadge = 'var(--color-primary)';
                            if ($u['role'] === 'superadmin') $roleBadge = '#7C3AED';
                            if ($u['role'] === 'gestionnaire') $roleBadge = '#2563EB';
                        ?>
                        <span style="font-size: 0.75rem; font-weight: 700; background-color: #F1F5F9; color: <?= $roleBadge ?>; padding: 4px 10px; border-radius: 9999px; text-transform: uppercase;">
                            <?= htmlspecialchars($u['role']) ?>
                        </span>
                    </td>
                    <td>
                        <div><?= htmlspecialchars($u['service'] ?? 'Services Généraux') ?></div>
                        <small style="color: var(--color-text-muted);"><?= htmlspecialchars($u['ministere'] ?? 'MEF') ?></small>
                    </td>
                    <td><?= htmlspecialchars($u['telephone'] ?? 'N/A') ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="data-card">
    <div class="card-header-bar">
        <h3 class="card-header-title">Référentiel des Ministères de la République Togolaise (<?= count($ministeres) ?>)</h3>
    </div>
    <div class="table-responsive">
        <table class="app-table">
            <thead>
                <tr>
                    <th>Sigle</th>
                    <th>Dénomination Complète</th>
                    <th>Code Administratif</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ministeres as $min): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($min['sigle']) ?></strong></td>
                    <td><?= htmlspecialchars($min['nom']) ?></td>
                    <td><span class="code-badge"><?= htmlspecialchars($min['code']) ?></span></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>
