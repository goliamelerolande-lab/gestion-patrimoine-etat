<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Utilisateurs - GPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen overflow-hidden flex">
    
    <!-- SIDEBAR -->
    <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-2xl z-20 relative">
        <div class="h-20 flex items-center px-8 border-b border-slate-700/50">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-3"><i class="fas fa-landmark"></i></div>
            <span class="text-2xl font-bold">GPE.</span>
        </div>
        <nav class="flex-1 px-4 py-8 space-y-3">
            <a href="/" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-pie w-8 text-center"></i> Tableau de bord</a>
            <a href="/biens" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-box w-8 text-center"></i> Gestion des Biens</a>
            <a href="/sites" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-map-marker-alt w-8 text-center"></i> Sites & Localisations</a>
            <a href="/rapports" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-line w-8 text-center"></i> Rapports Globaux</a>
            <a href="/utilisateurs" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl border border-blue-500/20"><i class="fas fa-users w-8 text-center"></i> Utilisateurs</a>
            <a href="/notifications" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all"><i class="fas fa-bell w-8 text-center"></i> Notifications</a>        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 overflow-y-auto bg-white/80">
        <div class="mb-8 flex flex-wrap items-start justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Espace utilisateurs</h1>
                <p class="mt-1 text-slate-500">Comptes réellement enregistrés dans le service d’authentification.</p>
                <p class="mt-2 text-sm text-slate-500">Connecté : <span class="font-semibold text-slate-700">{{ session('admin_user.name') }}</span> · {{ session('admin_user.email') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button id="openCreateUser" type="button" class="rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700">+ Nouvel utilisateur</button>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:border-red-200 hover:bg-red-50 hover:text-red-700">Se déconnecter</button>
                </form>
            </div>
        </div>
        <div role="status" class="mb-6 rounded-xl border border-blue-200 bg-blue-50 p-4 text-sm text-blue-900">
            L’accès administrateur est protégé. Les comptes sont enregistrés dans PostgreSQL ; les mots de passe sont hachés et ne sont jamais affichés.
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">Nom</th>
                        <th class="px-6 py-4 font-bold">Email</th>
                        <th class="px-6 py-4 font-bold">Rôle</th>
                        <th class="px-6 py-4 font-bold">Statut</th>
                        <th class="px-6 py-4 font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $user['name'] }}</td>
                        <td class="px-6 py-4">{{ $user['email'] }}</td>
                        <td class="px-6 py-4"><span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($user['role']) }}</span></td>
                        <td class="px-6 py-4"><span class="rounded-full px-3 py-1 text-xs font-bold {{ $user['is_active'] ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-600' }}">{{ $user['is_active'] ? 'Actif' : 'Désactivé' }}</span></td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-2">
                                <button type="button" class="editUser rounded-lg border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100" data-id="{{ $user['id'] }}" data-name="{{ $user['name'] }}" data-email="{{ $user['email'] }}" data-role="{{ $user['role'] }}">Modifier</button>
                                @if ((int) $user['id'] !== (int) session('admin_user.id'))
                                    <button type="button" class="toggleUser rounded-lg border px-3 py-2 text-sm font-semibold {{ $user['is_active'] ? 'border-amber-200 text-amber-700 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}" data-id="{{ $user['id'] }}" data-active="{{ $user['is_active'] ? '1' : '0' }}">{{ $user['is_active'] ? 'Désactiver' : 'Activer' }}</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucun compte enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>

    <dialog id="userModal" class="w-[min(94vw,38rem)] rounded-2xl p-0 shadow-2xl backdrop:bg-slate-950/50">
        <form id="userForm" class="p-6 sm:p-8">
            <div class="mb-6 flex items-start justify-between gap-4">
                <div>
                    <h2 id="userModalTitle" class="text-2xl font-bold">Créer un utilisateur</h2>
                    <p id="passwordHelp" class="mt-1 text-sm text-slate-500">Le mot de passe doit contenir au moins 12 caractères.</p>
                </div>
                <button id="closeUserModal" type="button" aria-label="Fermer" class="rounded-lg px-3 py-1 text-2xl text-slate-400 hover:bg-slate-100">&times;</button>
            </div>
            <div id="userFormError" class="mb-4 hidden rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-800" role="alert"></div>
            <input id="userId" type="hidden">
            <div class="grid gap-4 sm:grid-cols-2">
                <label class="text-sm font-semibold">Nom complet
                    <input id="userName" name="name" required maxlength="120" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 font-normal outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </label>
                <label class="text-sm font-semibold">Adresse email
                    <input id="userEmail" name="email" type="email" required maxlength="150" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 font-normal outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </label>
                <label class="text-sm font-semibold">Rôle
                    <select id="userRole" name="role" required class="mt-2 w-full rounded-xl border border-slate-300 bg-white px-3 py-2.5 font-normal">
                        <option value="agent">Agent</option>
                        <option value="admin">Administrateur</option>
                    </select>
                </label>
                <label class="text-sm font-semibold">Mot de passe <span id="passwordOptional" class="hidden font-normal text-slate-400">(facultatif)</span>
                    <input id="userPassword" name="password" type="password" minlength="12" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 font-normal outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </label>
                <label class="text-sm font-semibold sm:col-span-2">Confirmer le mot de passe
                    <input id="userPasswordConfirmation" name="password_confirmation" type="password" minlength="12" autocomplete="new-password" class="mt-2 w-full rounded-xl border border-slate-300 px-3 py-2.5 font-normal outline-none focus:border-blue-500 focus:ring-4 focus:ring-blue-100">
                </label>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button id="cancelUserModal" type="button" class="rounded-xl bg-slate-100 px-4 py-2.5 font-semibold text-slate-600 hover:bg-slate-200">Annuler</button>
                <button id="saveUser" type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 font-semibold text-white hover:bg-blue-700">Enregistrer</button>
            </div>
        </form>
    </dialog>
    <div id="userToast" role="status" aria-live="polite" class="fixed bottom-5 right-5 z-50 hidden max-w-md rounded-xl bg-slate-900 px-5 py-4 text-sm font-semibold text-white shadow-2xl"></div>
    <script>
        const userModal = document.getElementById('userModal');
        const userForm = document.getElementById('userForm');
        const userFormError = document.getElementById('userFormError');
        const userPassword = document.getElementById('userPassword');
        const userPasswordConfirmation = document.getElementById('userPasswordConfirmation');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function openUserForm(user = null) {
            userForm.reset();
            userFormError.classList.add('hidden');
            document.getElementById('userId').value = user?.id ?? '';
            document.getElementById('userName').value = user?.name ?? '';
            document.getElementById('userEmail').value = user?.email ?? '';
            document.getElementById('userRole').value = user?.role ?? 'agent';
            const editing = Boolean(user);
            document.getElementById('userModalTitle').textContent = editing ? 'Modifier un utilisateur' : 'Créer un utilisateur';
            document.getElementById('passwordHelp').textContent = editing ? 'Laissez vide pour conserver le mot de passe actuel.' : 'Le mot de passe doit contenir au moins 12 caractères.';
            document.getElementById('passwordOptional').classList.toggle('hidden', !editing);
            userPassword.required = !editing;
            userPasswordConfirmation.required = !editing;
            userModal.showModal();
        }

        document.getElementById('openCreateUser').addEventListener('click', () => openUserForm());
        document.getElementById('closeUserModal').addEventListener('click', () => userModal.close());
        document.getElementById('cancelUserModal').addEventListener('click', () => userModal.close());

        document.querySelectorAll('.editUser').forEach((button) => button.addEventListener('click', () => openUserForm({
            id: button.dataset.id,
            name: button.dataset.name,
            email: button.dataset.email,
            role: button.dataset.role,
        })));

        userForm.addEventListener('submit', async (event) => {
            event.preventDefault();
            userFormError.classList.add('hidden');
            const userId = document.getElementById('userId').value;
            const payload = Object.fromEntries(new FormData(userForm).entries());
            delete payload._token;
            if (userId && !payload.password) {
                delete payload.password;
                delete payload.password_confirmation;
            }

            const saveButton = document.getElementById('saveUser');
            saveButton.disabled = true;
            saveButton.textContent = 'Enregistrement…';

            try {
                const response = await fetch(userId ? `/gestion/utilisateurs/${userId}` : '/gestion/utilisateurs', {
                    method: userId ? 'PUT' : 'POST',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify(payload),
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const validationMessage = result.errors ? Object.values(result.errors).flat()[0] : null;
                    throw new Error(validationMessage || result.message || 'Impossible d’enregistrer cet utilisateur.');
                }
                userModal.close();
                window.location.reload();
            } catch (error) {
                userFormError.textContent = error.message;
                userFormError.classList.remove('hidden');
            } finally {
                saveButton.disabled = false;
                saveButton.textContent = 'Enregistrer';
            }
        });

        document.querySelectorAll('.toggleUser').forEach((button) => button.addEventListener('click', async () => {
            const currentlyActive = button.dataset.active === '1';
            const verb = currentlyActive ? 'désactiver' : 'réactiver';
            if (!window.confirm(`Voulez-vous ${verb} ce compte ?`)) return;
            button.disabled = true;
            try {
                const response = await fetch(`/gestion/utilisateurs/${button.dataset.id}/statut`, {
                    method: 'PATCH',
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ is_active: !currentlyActive }),
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(result.message || 'Impossible de modifier le statut de ce compte.');
                window.location.reload();
            } catch (error) {
                window.alert(error.message);
                button.disabled = false;
            }
        }));
    </script>
</body>
</html>
