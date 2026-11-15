<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Biens - GPE</title>
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
            <a href="/biens" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl border border-blue-500/20"><i class="fas fa-box w-8 text-center"></i> Gestion des Biens</a>
            <a href="/sites" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-map-marker-alt w-8 text-center"></i> Sites & Localisations</a>
            <a href="/rapports" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-line w-8 text-center"></i> Rapports Globaux</a>
            <a href="/utilisateurs" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-users w-8 text-center"></i> Utilisateurs</a>
            <a href="/notifications" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all"><i class="fas fa-bell w-8 text-center"></i> Notifications</a>        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 overflow-y-auto bg-white/80">
                <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold">Gestion des biens</h1>
                <p class="mt-1 text-slate-500">Consultez, ajoutez, modifiez ou supprimez les actifs du registre.</p>
            </div>
            <button type="button" onclick="document.getElementById('modalNouveauBien').classList.remove('hidden')" class="rounded-xl bg-primary px-5 py-3 font-bold text-white shadow-lg hover:bg-emerald-800">
                <i class="fas fa-plus mr-2"></i> Nouveau bien
            </button>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">Code</th>
                        <th class="px-6 py-4 font-bold">Libellé</th>
                        <th class="px-6 py-4 font-bold">Valeur</th>
                        <th class="px-6 py-4 font-bold">État</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($biens as $bien)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $bien->code_inventaire }}</td>
                        <td class="px-6 py-4">{{ $bien->libelle }}</td>
                        <td class="px-6 py-4 text-emerald-600 font-bold">{{ number_format($bien->valeur_acquisition, 0, ',', ' ') }} FCFA</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $bien->etat == 'neuf' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                {{ ucfirst($bien->etat) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="ouvrirModalVoir({{ Js::from($bien) }})" title="Voir" class="rounded-lg bg-blue-50 p-2 text-blue-700 hover:bg-blue-600 hover:text-white"><i class="fas fa-eye"></i></button>
                                <button type="button" onclick="ouvrirModalModifier({{ Js::from($bien) }})" title="Modifier" class="rounded-lg bg-amber-50 p-2 text-amber-700 hover:bg-amber-500 hover:text-white"><i class="fas fa-pen"></i></button>
                                <button type="button" onclick="supprimerBien({{ $bien->id }}, this)" title="Supprimer" class="rounded-lg bg-red-50 p-2 text-red-700 hover:bg-red-600 hover:text-white"><i class="fas fa-trash"></i></button>
                                <a href="/affectations?bien_id={{ $bien->id }}" title="Affecter a un site" class="rounded-lg bg-violet-50 p-2 text-violet-700 hover:bg-violet-600 hover:text-white"><i class="fas fa-location-dot"></i></a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucun bien enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $biens->links() }}</div>
    </main>

    <div id="actionToast" role="status" aria-live="polite" class="hidden fixed bottom-5 right-5 z-[100] max-w-md rounded-xl bg-slate-900 px-5 py-4 text-sm font-semibold text-white shadow-2xl"></div>

    <div id="modalNouveauBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <form id="formNouveauBien" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between"><h2 class="text-xl font-bold">Ajouter un bien</h2><button type="button" onclick="fermerModal('modalNouveauBien')" aria-label="Fermer">✕</button></div>
            <p class="text-sm text-slate-500">Le code d’inventaire sera généré automatiquement.</p>
            <label class="block text-sm font-semibold">Libellé<input id="new_libelle" required maxlength="180" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
            <label class="block text-sm font-semibold">État<select id="new_etat" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"><option value="neuf">Neuf</option><option value="usage">En usage</option><option value="reforme">Réformé</option></select></label>
            <label class="block text-sm font-semibold">Date d'acquisition<input id="new_date" type="date" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
            <label class="block text-sm font-semibold">Valeur (FCFA)<input id="new_valeur" type="number" min="0" step="0.01" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
            <div class="flex justify-end gap-3"><button type="button" onclick="fermerModal('modalNouveauBien')" class="rounded-lg bg-slate-100 px-4 py-2">Annuler</button><button type="submit" class="rounded-lg bg-primary px-4 py-2 font-bold text-white">Enregistrer</button></div>
        </form>
    </div>

    <div id="modalModifierBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <form id="formModifierBien" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between"><h2 class="text-xl font-bold">Modifier le bien</h2><button type="button" onclick="fermerModal('modalModifierBien')" aria-label="Fermer">✕</button></div>
            <input id="edit_id" type="hidden">
            <label class="block text-sm font-semibold">Libellé<input id="edit_libelle" required maxlength="180" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
            <label class="block text-sm font-semibold">État<select id="edit_etat" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"><option value="neuf">Neuf</option><option value="usage">En usage</option><option value="reforme">Réformé</option></select></label>
            <label class="block text-sm font-semibold">Valeur (FCFA)<input id="edit_valeur" type="number" min="0" step="0.01" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
            <div class="flex justify-end gap-3"><button type="button" onclick="fermerModal('modalModifierBien')" class="rounded-lg bg-slate-100 px-4 py-2">Annuler</button><button type="submit" class="rounded-lg bg-primary px-4 py-2 font-bold text-white">Enregistrer</button></div>
        </form>
    </div>

    <div id="modalVoirBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <section class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-2xl">
            <div class="flex items-center justify-between"><h2 class="text-xl font-bold">Détail du bien</h2><button type="button" onclick="fermerModal('modalVoirBien')" aria-label="Fermer">✕</button></div>
            <p><span class="font-semibold">Code :</span> <span id="voir_code"></span></p>
            <p><span class="font-semibold">Libellé :</span> <span id="voir_libelle"></span></p>
            <p><span class="font-semibold">Date :</span> <span id="voir_date"></span></p>
            <p><span class="font-semibold">Valeur :</span> <span id="voir_valeur"></span></p>
            <p><span class="font-semibold">État :</span> <span id="voir_etat"></span></p>
            <div class="flex justify-end"><button type="button" onclick="fermerModal('modalVoirBien')" class="rounded-lg bg-slate-800 px-4 py-2 text-white">Fermer</button></div>
        </section>
    </div>

    <script>
        const apiBiens = '/api/patrimoine/biens';
        function fermerModal(id) { document.getElementById(id).classList.add('hidden'); }
        function notifier(message) { const toast = document.getElementById('actionToast'); toast.textContent = message; toast.classList.remove('hidden'); }
        function ouvrirModalVoir(bien) {
            document.getElementById('voir_code').textContent = bien.code_inventaire;
            document.getElementById('voir_libelle').textContent = bien.libelle;
            document.getElementById('voir_date').textContent = bien.date_acquisition;
            document.getElementById('voir_valeur').textContent = Number(bien.valeur_acquisition).toLocaleString('fr-FR') + ' FCFA';
            document.getElementById('voir_etat').textContent = String(bien.etat).toUpperCase();
            document.getElementById('modalVoirBien').classList.remove('hidden');
        }
        function ouvrirModalModifier(bien) {
            document.getElementById('edit_id').value = bien.id;
            document.getElementById('edit_libelle').value = bien.libelle;
            document.getElementById('edit_etat').value = bien.etat;
            document.getElementById('edit_valeur').value = bien.valeur_acquisition;
            document.getElementById('modalModifierBien').classList.remove('hidden');
        }
        async function envoyerBien(form, url, method, data, modalId, message) {
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            try {
                const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' }, body: JSON.stringify(data) });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(result.message || 'La demande n’a pas abouti.');
                fermerModal(modalId);
                notifier(message + (result.code_inventaire ? ' Code : ' + result.code_inventaire : '') + '. Actualisation…');
                window.setTimeout(() => window.location.reload(), 700);
            } catch (error) {
                alert(error.message || 'Erreur réseau. Vérifiez votre connexion puis réessayez.');
                button.disabled = false;
            }
        }
        document.getElementById('formNouveauBien').addEventListener('submit', function(event) {
            event.preventDefault();
            envoyerBien(this, apiBiens, 'POST', { libelle: document.getElementById('new_libelle').value.trim(), category_id: 1, date_acquisition: document.getElementById('new_date').value, valeur_acquisition: Number(document.getElementById('new_valeur').value), etat: document.getElementById('new_etat').value }, 'modalNouveauBien', 'Bien enregistré');
        });
        document.getElementById('formModifierBien').addEventListener('submit', function(event) {
            event.preventDefault();
            envoyerBien(this, apiBiens + '/' + document.getElementById('edit_id').value, 'PUT', { libelle: document.getElementById('edit_libelle').value.trim(), etat: document.getElementById('edit_etat').value, valeur_acquisition: Number(document.getElementById('edit_valeur').value) }, 'modalModifierBien', 'Bien modifié');
        });
        async function supprimerBien(id, button) {
            if (!confirm('Supprimer définitivement ce bien ?')) return;
            button.disabled = true;
            try {
                const response = await fetch(apiBiens + '/' + id, { method: 'DELETE', headers: { 'Accept': 'application/json' } });
                if (!response.ok) throw new Error('La suppression du bien a échoué.');
                notifier('Bien supprimé. Actualisation…');
                window.setTimeout(() => window.location.reload(), 700);
            } catch (error) { alert(error.message || 'Erreur réseau lors de la suppression.'); button.disabled = false; }
        }
    </script>
</body>
</html>
