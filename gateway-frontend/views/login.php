<?php
// Préparer la liste des comptes officiels et de démonstration
$profiles = [];
if (!empty($demoUsers) && is_array($demoUsers)) {
    foreach ($demoUsers as $du) {
        $r = strtolower($du['role'] ?? 'agent');
        $rk = 'agent';
        $title = 'Agent Public';
        $color = '#059669';
        $bg = '#ECFDF5';
        $border = '#A7F3D0';
        $icon = 'fa-user';

        if (strpos($r, 'admin') !== false || strpos($r, 'super') !== false) {
            $rk = 'superadmin';
            $title = 'Super-Admin (DGPE)';
            $color = '#7C3AED';
            $bg = '#F5F3FF';
            $border = '#DDD6FE';
            $icon = 'fa-shield-halved';
        } elseif (strpos($r, 'gest') !== false) {
            $rk = 'gestionnaire';
            $title = 'Gestionnaire (MEF)';
            $color = '#2563EB';
            $bg = '#EFF6FF';
            $border = '#BFDBFE';
            $icon = 'fa-user-tie';
        } elseif (strpos($r, 'audit') !== false || strpos($r, 'inspect') !== false) {
            $rk = 'auditeur';
            $title = 'Auditeur (IGF Lomé)';
            $color = '#D97706';
            $bg = '#FFFBEB';
            $border = '#FDE68A';
            $icon = 'fa-clipboard-check';
        }

        $profiles[] = [
            'id' => $du['id'] ?? 1,
            'name' => $du['name'] ?? '',
            'email' => $du['email'] ?? '',
            'matricule' => $du['matricule'] ?? '',
            'role_key' => $rk,
            'title' => $title,
            'color' => $color,
            'bg' => $bg,
            'border' => $border,
            'icon' => $icon,
            'password_plain' => !empty($du['password_plain']) ? $du['password_plain'] : 'password123'
        ];
    }
}

// Secours garanti pour la soutenance
if (empty($profiles)) {
    $profiles = [
        [
            'id' => 1,
            'name' => 'GOLI Amele Rolande',
            'email' => 'admin@patrimoine.gouv.tg',
            'matricule' => 'MAT-TG-001',
            'role_key' => 'superadmin',
            'title' => 'Super-Admin (DGPE)',
            'color' => '#7C3AED',
            'bg' => '#F5F3FF',
            'border' => '#DDD6FE',
            'icon' => 'fa-shield-halved',
            'password_plain' => 'password123'
        ],
        [
            'id' => 2,
            'name' => "GODHOPE'S",
            'email' => 'gestionnaire@patrimoine.gouv.tg',
            'matricule' => 'MAT-TG-002',
            'role_key' => 'gestionnaire',
            'title' => 'Gestionnaire (MEF)',
            'color' => '#2563EB',
            'bg' => '#EFF6FF',
            'border' => '#BFDBFE',
            'icon' => 'fa-user-tie',
            'password_plain' => 'password123'
        ],
        [
            'id' => 3,
            'name' => 'MY ALL',
            'email' => 'agent@patrimoine.gouv.tg',
            'matricule' => 'MAT-TG-003',
            'role_key' => 'agent',
            'title' => 'Agent Public (MENTD)',
            'color' => '#059669',
            'bg' => '#ECFDF5',
            'border' => '#A7F3D0',
            'icon' => 'fa-user',
            'password_plain' => 'password123'
        ],
        [
            'id' => 4,
            'name' => 'Komi AMEGAN',
            'email' => 'auditeur@patrimoine.gouv.tg',
            'matricule' => 'MAT-TG-004',
            'role_key' => 'auditeur',
            'title' => 'Auditeur (IGF Lomé)',
            'color' => '#D97706',
            'bg' => '#FFFBEB',
            'border' => '#FDE68A',
            'icon' => 'fa-clipboard-check',
            'password_plain' => 'password123'
        ],
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Sécurisée | GEST-PATRIMOINE - République Togolaise</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <link rel="stylesheet" href="/css/app.css">
    <style>
        body {
            background: linear-gradient(135deg, #0D3B66 0%, #082440 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 24px 16px;
        }
        .login-card {
            background-color: #FFFFFF;
            width: 100%;
            max-width: 520px;
            border-radius: var(--radius-lg);
            padding: 36px 32px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.28);
        }
        .login-brand-header {
            text-align: center;
            margin-bottom: 24px;
        }
        .login-brand-icon {
            width: 52px;
            height: 52px;
            background: linear-gradient(135deg, var(--color-accent), #8A6D3B);
            color: #FFFFFF;
            font-size: 1.55rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .login-brand-header h2 {
            font-size: 1.35rem;
            color: var(--color-primary);
            font-weight: 800;
        }
        .login-brand-header p {
            font-size: 0.8rem;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 4px;
        }
        .demo-credentials-box {
            background-color: #F8FAFC;
            border: 1px dashed var(--color-border);
            padding: 16px;
            border-radius: var(--radius-md);
            margin-top: 24px;
        }
        .demo-profiles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-top: 10px;
        }
        @media (max-width: 520px) {
            .demo-profiles-grid {
                grid-template-columns: 1fr;
            }
            .login-card {
                padding: 24px 18px;
            }
        }
        .profile-demo-btn {
            background: #FFFFFF;
            border: 1px solid var(--color-border);
            border-radius: var(--radius-md);
            padding: 10px 12px;
            text-align: left;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            flex-direction: column;
            gap: 4px;
            position: relative;
        }
        .profile-demo-btn:hover {
            border-color: var(--color-primary);
            box-shadow: 0 4px 12px rgba(13, 59, 102, 0.12);
            transform: translateY(-2px);
        }
        .profile-demo-btn .role-tag {
            font-size: 0.68rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 2px 7px;
            border-radius: 9999px;
            width: fit-content;
        }
        .profile-demo-btn .user-title-name {
            font-size: 0.88rem;
            font-weight: 800;
            color: var(--color-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .profile-demo-btn .user-sub-info {
            font-size: 0.72rem;
            color: var(--color-text-muted);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .btn-direct-login {
            margin-top: 4px;
            padding: 4px 8px;
            font-size: 0.72rem;
            font-weight: 700;
            background-color: var(--color-primary);
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            transition: background 0.2s;
        }
        .btn-direct-login:hover {
            background-color: var(--color-primary-light);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-brand-header">
        <div class="login-brand-icon">
            <i class="fa-solid fa-landmark"></i>
        </div>
        <h2>GEST-PATRIMOINE TOGO</h2>
        <p>Patrimoine de l'État • République Togolaise</p>
        <div style="font-size: 0.76rem; color: #059669; font-weight: 700; margin-top: 5px; letter-spacing: 0.5px;">Travail - Liberté - Patrie</div>
    </div>

    <?php if (!empty($error)): ?>
        <div style="background-color: var(--color-danger-bg); color: var(--color-danger); padding: 12px; border-radius: var(--radius-md); font-size: 0.85rem; margin-bottom: 20px; font-weight: 600;">
            <i class="fa-solid fa-triangle-exclamation"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="/login" id="loginForm">
        <div class="form-group">
            <label style="font-weight: 700;">Identifiant (Email, Nom ou Matricule)</label>
            <input type="text" name="email" id="loginEmail" class="form-control" placeholder="ex: admin@patrimoine.gouv.tg ou GODHOPE'S" required autocomplete="username">
        </div>

        <div class="form-group">
            <label style="font-weight: 700;">Mot de passe</label>
            <div style="position: relative;">
                <input type="password" name="password" id="loginPassword" class="form-control" placeholder="Entrez votre mot de passe" required autocomplete="current-password" style="padding-right: 42px;">
                <button type="button" onclick="togglePasswordVisibility();" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--color-text-muted); font-size: 1.1rem; padding: 4px;" title="Afficher ou masquer le mot de passe">
                    <i class="fa-solid fa-eye" id="togglePasswordIcon"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 10px; font-size: 0.95rem; font-weight: 700;">
            <i class="fa-solid fa-lock"></i> Se Connecter au Système
        </button>
    </form>

    <!-- Comptes Démonstration & Soutenance (4 Profils Complets dont AUDITEUR) -->
    <div class="demo-credentials-box">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
            <div style="font-weight: 800; color: var(--color-primary); font-size: 0.85rem;">
                <i class="fa-solid fa-users"></i> Profils Officiels & Démonstration :
            </div>
            <span style="font-size: 0.72rem; color: #059669; font-weight: 700;">1-Clic Soutenance</span>
        </div>
        <p style="color: var(--color-text-muted); font-size: 0.74rem; margin-bottom: 8px;">
            Cliquez sur un profil pour remplir les identifiants ou vous connecter immédiatement :
        </p>

        <div class="demo-profiles-grid">
            <?php foreach ($profiles as $p): ?>
                <div class="profile-demo-btn" 
                     data-login="<?= htmlspecialchars($p['email'], ENT_QUOTES, 'UTF-8') ?>" 
                     data-pass="<?= htmlspecialchars($p['password_plain'], ENT_QUOTES, 'UTF-8') ?>" 
                     onclick="fillFromCard(this);">
                    <div class="role-tag" style="background-color: <?= $p['bg'] ?>; color: <?= $p['color'] ?>; border: 1px solid <?= $p['border'] ?>;">
                        <i class="fa-solid <?= $p['icon'] ?>"></i> <?= htmlspecialchars($p['title']) ?>
                    </div>
                    <div class="user-title-name" title="<?= htmlspecialchars($p['name']) ?>">
                        <?= htmlspecialchars($p['name']) ?>
                    </div>
                    <div class="user-sub-info">
                        <?= htmlspecialchars($p['email']) ?>
                    </div>
                    <button type="button" class="btn-direct-login" onclick="event.stopPropagation(); directLoginFromCard(this);">
                        <i class="fa-solid fa-arrow-right-to-bracket"></i> Connexion 1-Clic
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
function fillFromCard(el) {
    const card = el.closest('.profile-demo-btn') || el;
    const login = card.getAttribute('data-login') || '';
    const pass = card.getAttribute('data-pass') || '';
    fillCredentials(login, pass);
}

function directLoginFromCard(btn) {
    const card = btn.closest('.profile-demo-btn');
    if (!card) return;
    const login = card.getAttribute('data-login') || '';
    const pass = card.getAttribute('data-pass') || '';
    directLogin(login, pass);
}

function fillCredentials(login, pass) {
    document.getElementById('loginEmail').value = login;
    document.getElementById('loginPassword').value = pass;
    document.getElementById('loginPassword').focus();
}

function directLogin(login, pass) {
    fillCredentials(login, pass);
    document.getElementById('loginForm').submit();
}

function togglePasswordVisibility() {
    const input = document.getElementById('loginPassword');
    const icon = document.getElementById('togglePasswordIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        icon.style.color = '#0D3B66';
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        icon.style.color = '';
    }
}
</script>

</body>
</html>
