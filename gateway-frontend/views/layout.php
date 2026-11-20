<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'GEST-PATRIMOINE' ?> | République Togolaise</title>
    
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
                <p>Patrimoine de l'État Togolais</p>
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

            <div class="menu-category">États & Sceau Officiel</div>
            <a href="/rapports" class="nav-item <?= ($currentPage ?? '') === 'rapports' ? 'active' : '' ?>">
                <i class="fa-solid fa-file-invoice-dollar"></i>
                <span>Rapports Globaux</span>
            </a>
            <a href="/cachet" class="nav-item <?= in_array($currentPage ?? '', ['cachet', 'parametres', 'signature']) ? 'active' : '' ?>">
                <i class="fa-solid fa-stamp"></i>
                <span>Cachet & Signature</span>
                <span class="badge-tag-official">Paramètres</span>
            </a>
            <a href="/utilisateurs" class="nav-item <?= ($currentPage ?? '') === 'utilisateurs' ? 'active' : '' ?>">
                <i class="fa-solid fa-users-gear"></i>
                <span>Annuaire & Rôles</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/utilisateurs?modal=profile" class="user-mini-card" style="text-decoration: none; color: inherit; flex: 1; cursor: pointer;" title="Modifier mon nom d'utilisateur et mon mot de passe">
                <div class="user-avatar-circle">
                    <?= substr($_SESSION['user']['name'] ?? 'Amele GOLI', 0, 2) ?>
                </div>
                <div>
                    <div class="user-name-small"><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Amele Rolande GOLI') ?></div>
                    <div class="user-role-badge"><i class="fa-solid fa-pen-to-square" style="font-size: 0.65rem; margin-right: 3px;"></i> <?= htmlspecialchars($_SESSION['user']['role'] ?? 'Super-Admin') ?></div>
                </div>
            </a>
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
                <p><?= $pageSubtitle ?? 'Système National Intégré de Suivi du Patrimoine de l\'État Togolais' ?></p>
            </div>
            <div class="topbar-actions">
                <!-- Indicateur d'actualisation automatique -->
                <div class="auto-refresh-pill" id="autoRefreshPill" title="Actualisation automatique activée toutes les 30s">
                    <span class="refresh-dot" id="refreshDot"></span>
                    <span>Sync Auto: <strong id="refreshTimerCountdown">30s</strong></span>
                    <button type="button" class="btn-refresh-now" id="btnRefreshNow" title="Actualiser immédiatement" onclick="triggerManualRefresh()">
                        <i class="fa-solid fa-arrows-rotate" id="refreshIcon"></i>
                    </button>
                </div>
                <span class="badge-official" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; font-weight: 700;">
                    <i class="fa-solid fa-flag" style="color: #059669;"></i> Togo • Travail - Liberté - Patrie
                </span>
            </div>
        </header>

        <main class="app-content">
            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<!-- Scripts globaux : Disparition automatique des alertes & Actualisation automatique intelligente -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. DISPARITION AUTOMATIQUE DES ALERTES & NETTOYAGE IMMÉDIAT DES PARAMÈTRES D'URL
    const alertSelectors = [
        '.alert-notification',
        '.alert-auto-dismiss',
        'div[style*="background-color: var(--color-success-bg)"]',
        'div[style*="background-color: var(--color-danger-bg)"]',
        'div[style*="background-color: #FEF3C7"]',
        'div[style*="background-color: #EFF6FF"]'
    ];
    
    const alerts = document.querySelectorAll(alertSelectors.join(', '));
    
    if (alerts.length > 0) {
        alerts.forEach(function(alert) {
            // Appliquer styles de transition fluide
            alert.style.transition = 'opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
            alert.style.overflow = 'hidden';
            alert.style.position = 'relative';

            // Ajouter le bouton de fermeture rapide 'X' s'il n'existe pas
            if (!alert.querySelector('.alert-close-btn')) {
                const closeBtn = document.createElement('button');
                closeBtn.type = 'button';
                closeBtn.className = 'alert-close-btn';
                closeBtn.innerHTML = '&times;';
                closeBtn.setAttribute('title', 'Fermer cette notification');
                closeBtn.style.cssText = 'position: absolute; right: 14px; top: 50%; transform: translateY(-50%); background: none; border: none; font-size: 1.4rem; font-weight: bold; cursor: pointer; color: inherit; opacity: 0.6; line-height: 1;';
                closeBtn.onclick = function() {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => alert.remove(), 400);
                };
                alert.appendChild(closeBtn);
            }

            // Ajouter une barre de progression de temps
            if (!alert.querySelector('.alert-timer-bar')) {
                const timerBar = document.createElement('div');
                timerBar.className = 'alert-timer-bar';
                alert.appendChild(timerBar);
            }

            // Disparition automatique après 3.2 secondes
            setTimeout(function() {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-8px)';
                alert.style.maxHeight = alert.scrollHeight + 'px';
                setTimeout(function() {
                    alert.style.maxHeight = '0';
                    alert.style.marginTop = '0';
                    alert.style.marginBottom = '0';
                    alert.style.paddingTop = '0';
                    alert.style.paddingBottom = '0';
                }, 40);
                setTimeout(function() {
                    alert.remove();
                }, 520);
            }, 3200);
        });

        // Nettoyer immédiatement l'URL pour ne pas réafficher l'alerte au prochain rafraîchissement
        const url = new URL(window.location.href);
        const paramsToClean = ['success', 'updated', 'deleted', 'cachet_saved', 'cachet_reset', 'error_cachet', 'settings_saved', 'sig_saved', 'sig_reset', 'error_sig', 'profile_updated'];
        let hasParam = false;
        paramsToClean.forEach(function(param) {
            if (url.searchParams.has(param)) {
                url.searchParams.delete(param);
                hasParam = true;
            }
        });
        if (hasParam) {
            const cleanUrl = url.pathname + (url.search ? url.search : '');
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

    // 2. ACTUALISATION AUTOMATIQUE INTELLIGENTE (SYNC PÉRIODIQUE 30 SECONDES)
    let countdown = 30;
    const countdownEl = document.getElementById('refreshTimerCountdown');
    const refreshDot = document.getElementById('refreshDot');
    const refreshIcon = document.getElementById('refreshIcon');

    // Vérifier si l'utilisateur est en train d'interagir (pour ne jamais interrompre sa saisie)
    function canSafelyRefresh() {
        // 1. Ne pas rafraîchir si un modal de création, édition ou consultation est ouvert
        if (document.querySelector('.modal-overlay.active')) return false;
        // 2. Ne pas rafraîchir si un champ de formulaire ou de recherche est en cours de saisie
        const active = document.activeElement;
        if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT')) {
            return false;
        }
        // 3. Ne pas rafraîchir si l'onglet est en arrière-plan
        if (document.hidden) return false;
        return true;
    }

    // Fonction d'actualisation manuelle déclenchable au clic
    window.triggerManualRefresh = function() {
        if (refreshIcon) refreshIcon.classList.add('fa-spin');
        window.location.reload();
    };

    // Minuteur d'actualisation automatique
    setInterval(function() {
        if (!canSafelyRefresh()) {
            if (refreshDot) refreshDot.classList.add('paused');
            if (countdownEl) countdownEl.innerText = 'pause';
            return;
        }
        if (refreshDot) refreshDot.classList.remove('paused');
        countdown--;
        if (countdownEl) countdownEl.innerText = countdown + 's';
        if (countdown <= 0) {
            countdown = 30;
            if (refreshIcon) refreshIcon.classList.add('fa-spin');
            window.location.reload();
        }
    }, 1000);
});
</script>

</body>
</html>
