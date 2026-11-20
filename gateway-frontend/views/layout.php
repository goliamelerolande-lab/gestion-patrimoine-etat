<?php
$currentUser = $_SESSION['user'] ?? [
    'id' => 1,
    'name' => 'Amele Rolande GOLI',
    'email' => 'admin@patrimoine.gouv.tg',
    'role' => 'superadmin',
    'telephone' => '+228 90 00 00 01'
];

$rawRole = strtolower($currentUser['role'] ?? 'superadmin');
if (strpos($rawRole, 'admin') !== false) {
    $roleKey = 'superadmin';
    $roleTitle = 'Super-Administrateur';
    $roleBadgeText = 'Super-Admin • DGPE';
    $roleColor = '#7C3AED';
    $roleBg = '#F5F3FF';
    $roleBorder = '#DDD6FE';
    $roleIcon = 'fa-shield-halved';
} elseif (strpos($rawRole, 'gest') !== false) {
    $roleKey = 'gestionnaire';
    $roleTitle = 'Gestionnaire du Patrimoine';
    $roleBadgeText = 'Gestionnaire • MEF';
    $roleColor = '#2563EB';
    $roleBg = '#EFF6FF';
    $roleBorder = '#BFDBFE';
    $roleIcon = 'fa-user-tie';
} else {
    $roleKey = 'agent';
    $roleTitle = 'Agent Public';
    $roleBadgeText = 'Agent • MENTD';
    $roleColor = '#059669';
    $roleBg = '#ECFDF5';
    $roleBorder = '#A7F3D0';
    $roleIcon = 'fa-user';
}
?>
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
            <?php if ($roleKey === 'superadmin'): ?>
                <!-- 1. MENU SUPER-ADMINISTRATEUR (DGPE) : TOUTES LES FONCTIONNALITÉS -->
                <div class="menu-category">Gouvernance DGPE</div>
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

                <div class="menu-category">Sceau & Sécurité</div>
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

            <?php elseif ($roleKey === 'gestionnaire'): ?>
                <!-- 2. MENU GESTIONNAIRE DU PATRIMOINE (MEF) : OPÉRATIONS COURANTES DU PARC -->
                <div class="menu-category">Pilotage Opérationnel</div>
                <a href="/" class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-chart-line"></i>
                    <span>Tableau de Bord Parc</span>
                </a>

                <div class="menu-category">Gestion du Matériel</div>
                <a href="/biens" class="nav-item <?= ($currentPage ?? '') === 'biens' ? 'active' : '' ?>">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Registre des Biens</span>
                </a>
                <a href="/affectations" class="nav-item <?= ($currentPage ?? '') === 'affectations' ? 'active' : '' ?>">
                    <i class="fa-solid fa-people-carry-box"></i>
                    <span>Mouvements & Décharges</span>
                </a>
                <a href="/maintenances" class="nav-item <?= ($currentPage ?? '') === 'maintenances' ? 'active' : '' ?>">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                    <span>Maintenance & Pannes</span>
                </a>

                <div class="menu-category">Justificatifs</div>
                <a href="/rapports" class="nav-item <?= ($currentPage ?? '') === 'rapports' ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-lines"></i>
                    <span>Rapports d'Inventaire</span>
                </a>

            <?php else: ?>
                <!-- 3. MENU AGENT PUBLIC BÉNÉFICIAIRE : ESPACE PERSONNEL ÉPURÉ -->
                <div class="menu-category">Espace Fonctionnaire</div>
                <a href="/" class="nav-item <?= ($currentPage ?? '') === 'dashboard' ? 'active' : '' ?>">
                    <i class="fa-solid fa-id-badge"></i>
                    <span>Mon Espace Personnel</span>
                </a>

                <div class="menu-category">Mes Équipements</div>
                <a href="/biens" class="nav-item <?= ($currentPage ?? '') === 'biens' ? 'active' : '' ?>">
                    <i class="fa-solid fa-laptop-file"></i>
                    <span>Matériel Confié</span>
                </a>
                <a href="/affectations" class="nav-item <?= ($currentPage ?? '') === 'affectations' ? 'active' : '' ?>">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Mes Décharges</span>
                </a>

                <div class="menu-category">Assistance Technique</div>
                <a href="/maintenances" class="nav-item <?= ($currentPage ?? '') === 'maintenances' ? 'active' : '' ?>">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Signaler une Panne</span>
                </a>
            <?php endif; ?>
        </nav>

        <div class="sidebar-footer">
            <button type="button" class="user-mini-card" onclick="openGlobalProfileModal();" style="text-decoration: none; color: inherit; flex: 1; cursor: pointer; background: none; border: none; text-align: left; padding: 0;" title="Modifier mon nom d'utilisateur et mon mot de passe">
                <div class="user-avatar-circle" style="background: linear-gradient(135deg, <?= $roleColor ?>, var(--color-primary-light));">
                    <?= htmlspecialchars(substr($currentUser['name'] ?? 'AG', 0, 2)) ?>
                </div>
                <div style="overflow: hidden;">
                    <div class="user-name-small" style="white-space: nowrap; text-overflow: ellipsis; overflow: hidden;">
                        <?= htmlspecialchars($currentUser['name'] ?? 'Amele Rolande GOLI') ?>
                    </div>
                    <div class="user-role-badge" style="color: <?= $roleColor ?>; background-color: <?= $roleBg ?>; display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 9999px; font-size: 0.7rem; font-weight: 700;">
                        <i class="fa-solid <?= $roleIcon ?>"></i> <?= htmlspecialchars($roleBadgeText) ?>
                    </div>
                </div>
            </button>
            <a href="/logout" class="logout-btn" title="Déconnexion du système">
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
                <!-- Badge de Rôle Actuel -->
                <div style="background-color: <?= $roleBg ?>; border: 1px solid <?= $roleBorder ?>; color: <?= $roleColor ?>; font-weight: 700; padding: 6px 14px; border-radius: 9999px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 7px;">
                    <i class="fa-solid <?= $roleIcon ?>"></i>
                    <span><?= htmlspecialchars($roleTitle) ?></span>
                </div>

                <!-- Indicateur d'actualisation automatique -->
                <div class="auto-refresh-pill" id="autoRefreshPill" title="Actualisation automatique activée toutes les 30s">
                    <span class="refresh-dot" id="refreshDot"></span>
                    <span>Sync: <strong id="refreshTimerCountdown">30s</strong></span>
                    <button type="button" class="btn-refresh-now" id="btnRefreshNow" title="Actualiser immédiatement" onclick="triggerManualRefresh()">
                        <i class="fa-solid fa-arrows-rotate" id="refreshIcon"></i>
                    </button>
                </div>

                <span class="badge-official" style="background-color: #ECFDF5; border-color: #A7F3D0; color: #065F46; font-weight: 700;">
                    <i class="fa-solid fa-flag" style="color: #059669;"></i> Togo
                </span>
            </div>
        </header>

        <main class="app-content">
            <!-- Alerte de restriction de droits RBAC -->
            <?php if (!empty($_GET['unauthorized'])): ?>
                <div class="alert-notification alert-danger alert-auto-dismiss" style="margin-bottom: 20px;">
                    <div class="alert-icon-text">
                        <i class="fa-solid fa-shield-halved" style="font-size: 1.3rem;"></i>
                        <div>
                            <strong>Accès restreint par les règles de sécurité de l'État :</strong><br>
                            Votre profil actuel (<strong><?= htmlspecialchars($roleTitle) ?></strong>) ne dispose pas des privilèges nécessaires pour accéder à cette fonctionnalité.
                        </div>
                    </div>
                    <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
                    <div class="alert-timer-bar"></div>
                </div>
            <?php endif; ?>

            <!-- Alerte profil mis à jour -->
            <?php if (!empty($_GET['profile_updated'])): ?>
                <div class="alert-notification alert-success alert-auto-dismiss" style="margin-bottom: 20px;">
                    <div class="alert-icon-text">
                        <i class="fa-solid fa-circle-check" style="font-size: 1.3rem;"></i>
                        <div>
                            <strong>Vos coordonnées et identifiants ont été mis à jour avec succès !</strong><br>
                            Les modifications sont immédiatement actives pour votre session.
                        </div>
                    </div>
                    <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
                    <div class="alert-timer-bar"></div>
                </div>
            <?php endif; ?>

            <?= $content ?? '' ?>
        </main>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODALE GLOBALE DE MODIFICATION DU PROFIL & MOT DE PASSE (Tous Rôles)      -->
<!-- ========================================================================= -->
<div class="modal-overlay" id="globalProfileModal">
    <div class="modal-box" style="max-width: 520px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-user-pen" style="color: <?= $roleColor ?>;"></i> Mon Profil Utilisateur</h3>
            <button class="modal-close" onclick="closeGlobalProfileModal();">&times;</button>
        </div>
        <form method="POST" action="/profil/update" class="modal-body">
            <input type="hidden" name="id" value="<?= (int)($currentUser['id'] ?? 1) ?>">
            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($_SERVER['REQUEST_URI'] ?? '/') ?>">

            <div style="background-color: <?= $roleBg ?>; border: 1px solid <?= $roleBorder ?>; padding: 12px 14px; border-radius: var(--radius-md); margin-bottom: 18px; display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 50%; background-color: <?= $roleColor ?>; color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 800;">
                    <?= htmlspecialchars(substr($currentUser['name'] ?? 'AG', 0, 2)) ?>
                </div>
                <div>
                    <div style="font-weight: 800; color: var(--color-primary); font-size: 0.95rem;">
                        <?= htmlspecialchars($currentUser['name'] ?? 'Amele Rolande GOLI') ?>
                    </div>
                    <div style="font-size: 0.78rem; color: <?= $roleColor ?>; font-weight: 700;">
                        <i class="fa-solid <?= $roleIcon ?>"></i> <?= htmlspecialchars($roleTitle) ?>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.85rem;">Nom et Prénoms *</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($currentUser['name'] ?? '') ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.85rem;">Email Institutionnel / Identifiant *</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($currentUser['email'] ?? '') ?>" required>
            </div>

            <div class="form-group" style="margin-bottom: 14px;">
                <label style="font-weight: 700; font-size: 0.85rem;">Téléphone Professionnel (Togo)</label>
                <input type="text" name="telephone" class="form-control" value="<?= htmlspecialchars($currentUser['telephone'] ?? '') ?>" placeholder="+228 90 00 00 00">
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label style="font-weight: 700; font-size: 0.85rem;">Nouveau Mot de Passe (laisser vide pour conserver l'actuel)</label>
                <div style="position: relative;">
                    <input type="password" name="password" id="profilePasswordInput" class="form-control" placeholder="••••••••••••" style="padding-right: 42px;">
                    <button type="button" onclick="toggleProfilePassword();" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--color-text-muted); font-size: 1.05rem;" title="Afficher ou masquer">
                        <i class="fa-solid fa-eye" id="profileEyeIcon"></i>
                    </button>
                </div>
                <small style="color: var(--color-text-muted); font-size: 0.74rem;">Pour les tests de soutenance, vous pouvez utiliser <code>password123</code></small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid var(--color-border); padding-top: 14px;">
                <button type="button" class="btn btn-outline" onclick="closeGlobalProfileModal();">Annuler</button>
                <button type="submit" class="btn btn-primary" style="background-color: <?= $roleColor ?>; border-color: <?= $roleColor ?>;">
                    <i class="fa-solid fa-floppy-disk"></i> Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Scripts globaux : Disparition automatique des alertes & Actualisation automatique intelligente -->
<script>
function openGlobalProfileModal() {
    document.getElementById('globalProfileModal').classList.add('active');
}

function closeGlobalProfileModal() {
    document.getElementById('globalProfileModal').classList.remove('active');
}

function toggleProfilePassword() {
    const input = document.getElementById('profilePasswordInput');
    const icon = document.getElementById('profileEyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

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
            alert.style.transition = 'opacity 0.5s cubic-bezier(0.4, 0, 0.2, 1), transform 0.5s ease, max-height 0.5s ease, margin 0.5s ease, padding 0.5s ease';
            alert.style.overflow = 'hidden';
            alert.style.position = 'relative';

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

            if (!alert.querySelector('.alert-timer-bar')) {
                const timerBar = document.createElement('div');
                timerBar.className = 'alert-timer-bar';
                alert.appendChild(timerBar);
            }

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
            }, 3500);
        });

        // Nettoyer immédiatement l'URL pour ne pas réafficher l'alerte au prochain rafraîchissement
        const url = new URL(window.location.href);
        const paramsToClean = ['success', 'updated', 'deleted', 'cachet_saved', 'cachet_reset', 'error_cachet', 'settings_saved', 'sig_saved', 'sig_reset', 'error_sig', 'profile_updated', 'unauthorized'];
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

    function canSafelyRefresh() {
        if (document.querySelector('.modal-overlay.active')) return false;
        const active = document.activeElement;
        if (active && (active.tagName === 'INPUT' || active.tagName === 'TEXTAREA' || active.tagName === 'SELECT')) {
            return false;
        }
        if (document.hidden) return false;
        return true;
    }

    window.triggerManualRefresh = function() {
        if (refreshIcon) refreshIcon.classList.add('fa-spin');
        window.location.reload();
    };

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
