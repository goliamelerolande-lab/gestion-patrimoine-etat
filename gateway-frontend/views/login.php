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
            padding: 20px;
        }
        .login-card {
            background-color: #FFFFFF;
            width: 100%;
            max-width: 460px;
            border-radius: var(--radius-lg);
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.25);
        }
        .login-brand-header {
            text-align: center;
            margin-bottom: 30px;
        }
        .login-brand-icon {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, var(--color-accent), #8A6D3B);
            color: #FFFFFF;
            font-size: 1.6rem;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 12px;
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
            font-size: 0.82rem;
        }
        .demo-btn-row {
            display: flex;
            gap: 8px;
            margin-top: 10px;
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

    <form method="POST" action="/login">
        <div class="form-group">
            <label>Identifiant / Email Institutionnel</label>
            <input type="email" name="email" id="loginEmail" class="form-control" placeholder="ex: admin@patrimoine.gouv.tg" required autocomplete="email">
        </div>

        <div class="form-group">
            <label style="font-weight: 600;">Mot de passe</label>
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

    <!-- Comptes Démonstration en 1 Clic pour la Soutenance -->
    <div class="demo-credentials-box">
        <div style="font-weight: 700; color: var(--color-primary); margin-bottom: 6px;">
            <i class="fa-solid fa-key"></i> Profils de Soutenance & Démonstration :
        </div>
        <p style="color: var(--color-text-muted); font-size: 0.75rem; margin-bottom: 8px;">Cliquez sur un profil pour remplir automatiquement les identifiants :</p>
        <div class="demo-btn-row">
            <button type="button" class="btn btn-outline btn-sm" onclick="setDemo('admin@patrimoine.gouv.tg', 'password123');" style="flex:1;">
                Super-Admin
            </button>
            <button type="button" class="btn btn-outline btn-sm" onclick="setDemo('gestionnaire@patrimoine.gouv.tg', 'password123');" style="flex:1;">
                Gestionnaire
            </button>
            <button type="button" class="btn btn-outline btn-sm" onclick="setDemo('agent@patrimoine.gouv.tg', 'password123');" style="flex:1;">
                Agent
            </button>
        </div>
    </div>
</div>

<script>
function setDemo(email, pass) {
    document.getElementById('loginEmail').value = email;
    document.getElementById('loginPassword').value = pass;
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
