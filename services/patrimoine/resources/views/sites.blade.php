<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sites & Localisations - GPE</title>
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
            <a href="/sites" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl border border-blue-500/20"><i class="fas fa-map-marker-alt w-8 text-center"></i> Sites & Localisations</a>
            <a href="/rapports" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-line w-8 text-center"></i> Rapports Globaux</a>
            <a href="/utilisateurs" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-users w-8 text-center"></i> Utilisateurs</a>
            <a href="/notifications" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all"><i class="fas fa-bell w-8 text-center"></i> Notifications</a>        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 overflow-y-auto bg-white/80">
                <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div><h1 class="text-3xl font-bold">Sites & Localisations</h1><p class="mt-1 text-slate-500">Gérez les lieux d’affectation du patrimoine.</p></div>
            <button type="button" onclick="ouvrirFormulaireSite()" class="rounded-xl bg-primary px-5 py-3 font-bold text-white shadow-lg hover:bg-emerald-800"><i class="fas fa-plus mr-2"></i>Nouveau site</button>
        </div>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">Code</th>
                        <th class="px-6 py-4 font-bold">Nom du site</th>
                        <th class="px-6 py-4 font-bold">Ville</th>
                        <th class="px-6 py-4 font-bold">Région</th>
                        <th class="px-6 py-4 font-bold text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($sites as $site)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $site['code'] }}</td>
                        <td class="px-6 py-4">{{ $site['nom'] }}</td>
                        <td class="px-6 py-4">{{ $site['ville'] }}</td>
                        <td class="px-6 py-4">{{ $site['region'] ?? '—' }}</td>
                        <td class="px-6 py-4 text-right whitespace-nowrap">
                            <button type="button" onclick="modifierSite({{ Js::from($site) }})" title="Modifier" class="rounded-lg bg-amber-50 p-2 text-amber-700 hover:bg-amber-500 hover:text-white"><i class="fas fa-pen"></i></button>
                            <button type="button" onclick="supprimerSite({{ $site['id'] }}, this)" title="Supprimer" class="rounded-lg bg-red-50 p-2 text-red-700 hover:bg-red-600 hover:text-white"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucun site enregistré.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div id="siteToast" role="status" aria-live="polite" class="hidden fixed bottom-5 right-5 z-[100] rounded-xl bg-slate-900 px-5 py-4 text-sm font-semibold text-white shadow-2xl"></div>
        <div id="siteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
            <form id="siteForm" class="w-full max-w-lg space-y-4 rounded-2xl bg-white p-6 shadow-2xl">
                <div class="flex items-center justify-between"><h2 id="siteModalTitle" class="text-xl font-bold">Nouveau site</h2><button type="button" onclick="fermerFormulaireSite()" aria-label="Fermer">✕</button></div>
                <input id="site_id" type="hidden">
                <label class="block text-sm font-semibold">Code du site (automatique)<input id="site_code" readonly placeholder="Généré à l’enregistrement" class="mt-1 w-full rounded-lg border border-slate-300 bg-slate-100 px-3 py-2 text-slate-500"></label>
                <label class="block text-sm font-semibold">Nom du site<input id="site_nom" required maxlength="120" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
                <label class="block text-sm font-semibold">Ville<input id="site_ville" maxlength="80" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
                <label class="block text-sm font-semibold">Région<input id="site_region" maxlength="80" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2"></label>
                <div class="flex justify-end gap-3"><button type="button" onclick="fermerFormulaireSite()" class="rounded-lg bg-slate-100 px-4 py-2">Annuler</button><button type="submit" class="rounded-lg bg-primary px-4 py-2 font-bold text-white">Enregistrer</button></div>
            </form>
        </div>
        <script>
            const sitesApi = '/api/localisation/sites';
            function ouvrirFormulaireSite() { document.getElementById('siteForm').reset(); document.getElementById('site_id').value=''; document.getElementById('site_code').value=''; document.getElementById('siteModalTitle').textContent='Nouveau site'; document.getElementById('siteModal').classList.remove('hidden'); }
            function fermerFormulaireSite() { document.getElementById('siteModal').classList.add('hidden'); }
            function modifierSite(site) { document.getElementById('site_id').value=site.id; document.getElementById('site_code').value=site.code; document.getElementById('site_nom').value=site.nom; document.getElementById('site_ville').value=site.ville || ''; document.getElementById('site_region').value=site.region || ''; document.getElementById('siteModalTitle').textContent='Modifier le site'; document.getElementById('siteModal').classList.remove('hidden'); }
            async function envoyerSite(event) {
                event.preventDefault();
                const id=document.getElementById('site_id').value;
                const form=event.currentTarget;
                const button=form.querySelector('button[type="submit"]');
                button.disabled=true;
                const data={nom:document.getElementById('site_nom').value.trim(),ville:document.getElementById('site_ville').value.trim() || null,region:document.getElementById('site_region').value.trim() || null};
                try {
                    const response=await fetch(id ? sitesApi+'/'+id : sitesApi,{method:id?'PUT':'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(data)});
                    const result=await response.json().catch(()=>({}));
                    if(!response.ok) throw new Error(result.message || 'Enregistrement du site impossible.');
                    fermerFormulaireSite();
                    window.location.href='/sites?fresh=1';
                } catch(error) { alert(error.message || 'Erreur réseau lors de l’enregistrement.'); button.disabled=false; }
            }
            document.getElementById('siteForm').addEventListener('submit',envoyerSite);
            async function supprimerSite(id,button) {
                if(!confirm('Supprimer définitivement ce site ?')) return;
                button.disabled=true;
                try { const response=await fetch(sitesApi+'/'+id,{method:'DELETE',headers:{'Accept':'application/json'}}); if(!response.ok) throw new Error('Suppression du site impossible.'); window.location.href='/sites?fresh=1'; }
                catch(error) { alert(error.message || 'Erreur réseau lors de la suppression.'); button.disabled=false; }
            }
        </script>
    </main>
</body>
</html>
