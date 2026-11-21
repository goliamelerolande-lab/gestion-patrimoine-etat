<?php
$pageTitle = 'Annuaire des Agents & Ministères (Togo)';
$pageSubtitle = 'Gestion des comptes administratifs, privilèges et rattachements ministériels de l\'État Togolais';
$currentPage = 'utilisateurs';
ob_start();
?>

<!-- Alerte de confirmation -->
<?php if (!empty($_GET['profile_updated'])): ?>
    <div class="alert-notification alert-success alert-auto-dismiss">
        <div class="alert-icon-text">
            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem;"></i>
            <div>
                <strong>Profil administrateur et identifiants mis à jour avec succès !</strong><br>
                Votre nouveau nom et/ou mot de passe sont immédiatement enregistrés et actifs.
            </div>
        </div>
        <button type="button" class="alert-close-btn" onclick="this.closest('.alert-notification').remove()">&times;</button>
        <div class="alert-timer-bar"></div>
    </div>
<?php endif; ?>

<!-- Carte de gestion du profil Administrateur connecté -->
<div class="data-card" style="margin-bottom: 24px; padding: 22px 24px; background: linear-gradient(135deg, #FFFFFF 0%, #F8FAFC 100%); border-left: 4px solid var(--color-primary);">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px;">
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="width: 52px; height: 52px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary), var(--color-primary-light)); color: #FFF; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; font-weight: 800;">
                <?= substr($_SESSION['user']['name'] ?? 'Amele GOLI', 0, 2) ?>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--color-primary); margin: 0;">
                        <?= htmlspecialchars($_SESSION['user']['name'] ?? 'Amele Rolande GOLI') ?>
                    </h3>
                    <span style="font-size: 0.72rem; font-weight: 700; background-color: #EDE9FE; color: #7C3AED; padding: 2px 8px; border-radius: 9999px; text-transform: uppercase;">
                        <?= htmlspecialchars($_SESSION['user']['role'] ?? 'Super-Administrateur') ?>
                    </span>
                </div>
                <div style="font-size: 0.85rem; color: var(--color-text-muted); margin-top: 3px;">
                    <i class="fa-regular fa-envelope" style="margin-right: 4px;"></i> Identifiant : <strong><?= htmlspecialchars($_SESSION['user']['email'] ?? 'admin@patrimoine.gouv.tg') ?></strong>
                    &bull; <i class="fa-solid fa-phone" style="margin-right: 4px;"></i> <?= htmlspecialchars($_SESSION['user']['telephone'] ?? '+228 90 00 00 01') ?>
                </div>
            </div>
        </div>

        <button type="button" class="btn btn-primary" onclick="openProfileModal(<?= (int)($_SESSION['user']['id'] ?? 1) ?>, '<?= htmlspecialchars(addslashes($_SESSION['user']['name'] ?? 'Amele Rolande GOLI')) ?>', '<?= htmlspecialchars(addslashes($_SESSION['user']['email'] ?? 'admin@patrimoine.gouv.tg')) ?>', '<?= htmlspecialchars(addslashes($_SESSION['user']['telephone'] ?? '+228 90 00 00 01')) ?>');" style="gap: 8px;">
            <i class="fa-solid fa-user-gear"></i>
            <span>Modifier mon Nom & Mot de Passe</span>
        </button>
    </div>
</div>

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
                    <th style="text-align: right;">Actions</th>
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
                            if ($u['role'] === 'auditeur') $roleBadge = '#D97706';
                            if ($u['role'] === 'agent') $roleBadge = '#059669';
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
                    <td style="text-align: right;">
                        <button type="button" class="btn btn-outline btn-sm" onclick="openProfileModal(<?= (int)$u['id'] ?>, '<?= htmlspecialchars(addslashes($u['name'])) ?>', '<?= htmlspecialchars(addslashes($u['email'])) ?>', '<?= htmlspecialchars(addslashes($u['telephone'] ?? '')) ?>', '<?= htmlspecialchars(addslashes($u['role'])) ?>');" style="padding: 4px 10px; font-size: 0.78rem;" title="Modifier cet utilisateur">
                            <i class="fa-solid fa-pen-to-square"></i> Modifier
                        </button>
                    </td>
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

<!-- ========================================================================= -->
<!-- MODALE DE MODIFICATION DU PROFIL & MOT DE PASSE                           -->
<!-- ========================================================================= -->
<div class="modal-overlay <?= (!empty($_GET['modal']) && $_GET['modal'] === 'profile') ? 'active' : '' ?>" id="editProfileModal">
    <div class="modal-box" style="max-width: 540px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-user-pen" style="color: var(--color-primary-light);"></i> Modifier le Nom & Mot de Passe</h3>
            <button class="modal-close" onclick="document.getElementById('editProfileModal').classList.remove('active');">&times;</button>
        </div>
        <form method="POST" action="/profil/update" class="modal-body">
            <input type="hidden" name="id" id="editUserId" value="1">
            <input type="hidden" name="redirect_to" value="/utilisateurs">

            <div class="form-group">
                <label style="font-weight: 700;">Nom & Prénoms *</label>
                <input type="text" name="name" id="editUserName" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['name'] ?? 'Amele Rolande GOLI') ?>" required placeholder="Ex: Amele Rolande GOLI">
            </div>

            <div class="form-group">
                <label style="font-weight: 700;">Identifiant / Email Institutionnel *</label>
                <input type="email" name="email" id="editUserEmail" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['email'] ?? 'admin@patrimoine.gouv.tg') ?>" required placeholder="admin@patrimoine.gouv.tg">
                <small style="color: var(--color-text-muted); font-size: 0.76rem;">Cet email sert d'identifiant pour vous connecter à la plateforme.</small>
            </div>

            <div class="form-group">
                <label style="font-weight: 700;">Téléphone professionnel</label>
                <input type="text" name="telephone" id="editUserPhone" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['telephone'] ?? '+228 90 00 00 01') ?>" placeholder="+228 XX XX XX XX">
            </div>

            <div class="form-group" style="margin-top: 14px;">
                <label style="font-weight: 700;">Rôle & Privilèges Système</label>
                <select name="role" id="editUserRole" class="form-control">
                    <option value="superadmin">SUPERADMIN • Direction Générale DGPE</option>
                    <option value="gestionnaire">GESTIONNAIRE • Logistique & Matériel MEF</option>
                    <option value="auditeur">AUDITEUR • Inspection Générale des Finances (IGF Lomé)</option>
                    <option value="agent">AGENT • Bénéficiaire de Dotation Publique</option>
                </select>
            </div>

            <div class="form-group" style="margin-top: 18px; border-top: 1px solid var(--color-border); padding-top: 16px;">
                <label style="font-weight: 700; color: #B91C1C;">
                    <i class="fa-solid fa-key"></i> Nouveau Mot de Passe
                </label>
                <input type="password" name="password" id="editUserPassword" class="form-control" placeholder="Entrez un nouveau mot de passe (ou laissez vide pour ne pas changer)" autocomplete="new-password">
                <small style="color: var(--color-text-muted); font-size: 0.76rem;">Laissez ce champ vide si vous souhaitez conserver votre mot de passe actuel.</small>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                <button type="button" class="btn btn-outline" onclick="document.getElementById('editProfileModal').classList.remove('active');">Annuler</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 700;">
                    <i class="fa-solid fa-check"></i> Enregistrer les Modifications
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openProfileModal(id, name, email, phone, role) {
    document.getElementById('editUserId').value = id;
    document.getElementById('editUserName').value = name;
    document.getElementById('editUserEmail').value = email;
    document.getElementById('editUserPhone').value = phone || '';
    if (document.getElementById('editUserRole')) {
        document.getElementById('editUserRole').value = role || 'agent';
    }
    document.getElementById('editUserPassword').value = '';
    document.getElementById('editProfileModal').classList.add('active');
}
</script>

<?php
$content = ob_get_clean();
require __DIR__ . '/layout.php';
?>