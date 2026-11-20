<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'GEST-PATRIMOINE' ?> | République de Côte d'Ivoire</title>
    
    <!-- Google Fonts & Font Awesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS Personnalisé -->
    <link rel="stylesheet" href="/css/app.css">
</head>
<body>

<div class="app-wrapper">
    <!-- Barre Latérale (Sidebar) -->
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <div class="brand-shield">
                <i class="fa-solid fa-landmark"></i>
            </div>
            <div class="brand-text">
                <h1>GEST-PATRIMOINE</h1>
                <p>Patrimoine de l'État</p>
            </div>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-category">Gouvernance</div>
            <a href="/" class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                <i class="fa-solid fa-chart-pie"></i>
                <span>Tableau de Bord</span>
            </a>

            <div class="menu-category">Inventaire & Biens</div>
            <a href="/biens" class="nav-item <?= ($currentPage ?? '') === 'biens' ? 'active' : '' ?>">
                <i class="fa-solid fa-boxes-stacked"></i>
                <span>Registre des Biens</span>
            </a>
            <a href="/affectations" class="nav-item <?= ($currentPage ?? '') === 'affectations' ? 'active' : '' ?>">
                <i class="fa-solid fa-people-carry-box"></i>
                <span>Affectations</span>
            </a>
            <a href="/maintenances" class="nav-item <?= ($currentPage ?? '') === 'maintenances' ? 'active' : '' ?>">
                <i class="fa-solid fa-screwdriver-wrench"></i>
                <span>Maintenance & Pannes</span>
            </a>

            <div class="menu-category">États & Contrôle</div>
            <a href="/rapports" class="nav-item <?= ($currentPage ?? '') === 'rapports' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Rapports Globaux</span>
            </a>
            <a href="/utilisateurs" class="nav-item <?= ($currentPage ?? '') === 'utilisateurs' ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i>
                <span>Annuaire & Rôles</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-mini-card">
                <div class="user-avatar-circle">
                    <?= substr($_SESSION['user']['name'] ?? 'Amele GOLI', 0, 2) ?>
                </div>
                <div>
                    <div class="user-name-small"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Amele Rolande GOLI') ?></div>
                    <div class="user-role-badge"><?= htmlspecialchars($_SESSION['user']['role'] ?? 'Super-Admin') ?></div>
                </div>
            </div>
            <a href="/logout" class="logout-btn" title="Déconnexion">
                <i class="fa-solid fa-arrow-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <!-- Conteneur Principal -->
    <div class="app-main">
        <header class="app-topbar">
            <div class="page-title">
                <h2><?= $pageTitle ?? 'Tableau de Bord' ?></h2>
                <p><?= $pageSubtitle ?? 'Système National Intégré de Suivi du Patrimoine de l\'État' ?></p>
            </div>
            <div class="topbar-actions">
                <span class="badge-official">
                    <i class="fa-solid fa-circle-check" style="color: var(--color-success);"></i> Microservices Actifs (Docker)
                </span>
                <span class="badge-official">
                    <i class="fa-solid fa-database" style="color: #3B82F6;"></i> PostgreSQL 16
                </span>
            </div>
        </header>

        <main class="app-content">
            <?= $content ?? '' ?>
        </main>
    </div>
</div>

</body>
</html>
