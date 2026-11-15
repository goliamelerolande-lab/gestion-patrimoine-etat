<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Affectations - GPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 font-sans text-slate-800">
    <header class="bg-slate-900 text-white">
        <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-6 py-5">
            <a href="/" class="text-xl font-bold">GPE. <span class="font-normal text-slate-300">Gestion du patrimoine</span></a>
            <nav class="flex flex-wrap gap-2 text-sm">
                <a href="/biens" class="rounded-lg px-3 py-2 hover:bg-slate-800">Biens</a>
                <a href="/sites" class="rounded-lg px-3 py-2 hover:bg-slate-800">Sites</a>
                <a href="/affectations" class="rounded-lg bg-blue-600 px-3 py-2 font-semibold">Affectations</a>
                <a href="/notifications" class="rounded-lg px-3 py-2 hover:bg-slate-800">Notifications</a>
            </nav>
        </div>
    </header>

    <main class="mx-auto max-w-7xl space-y-8 px-6 py-10">
        <div>
            <h1 class="text-3xl font-bold tracking-tight">Affectations des biens</h1>
            <p class="mt-2 text-slate-600">Associez chaque bien &agrave; un site et conservez son historique de localisation.</p>
        </div>

        @if($biens->isEmpty() || $sites->isEmpty())
            <section class="rounded-2xl border border-amber-200 bg-amber-50 p-5 text-amber-900">
                @if($biens->isEmpty())
                    <p>Aucun bien n'est encore enregistr&eacute;. Ajoutez un bien avant de cr&eacute;er une affectation.</p>
                @elseif($sites->isEmpty())
                    <p>Aucun site n'est encore enregistr&eacute;. <a class="font-bold underline" href="/sites">Ajoutez un site</a> avant de cr&eacute;er une affectation.</p>
                @endif
            </section>
        @else
            <section class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-xl font-bold">Nouvelle affectation</h2>
                <p class="mt-1 text-sm text-slate-500">L'utilisateur connect&eacute; sera enregistr&eacute; comme responsable de l'op&eacute;ration.</p>
                <form id="affectationForm" class="mt-6 grid gap-5 md:grid-cols-2">
                    @csrf
                    <label class="text-sm font-semibold">Bien
                        <select name="bien_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5">
                            <option value="">Choisir un bien</option>
                            @foreach($biens as $bien)
                                <option value="{{ $bien->id }}" @selected($selectedBienId === (int) $bien->id)>{{ $bien->code_inventaire }} — {{ $bien->libelle }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-semibold">Site
                        <select name="site_id" required class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5">
                            <option value="">Choisir un site</option>
                            @foreach($sites as $site)
                                <option value="{{ $site['id'] }}">{{ $site['code'] }} — {{ $site['nom'] }} ({{ $site['ville'] ?? 'Ville non renseign&eacute;e' }})</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="text-sm font-semibold">Date de d&eacute;but
                        <input name="date_debut" type="date" required value="{{ now()->toDateString() }}" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5">
                    </label>
                    <label class="text-sm font-semibold">Date de fin <span class="font-normal text-slate-500">(facultative)</span>
                        <input name="date_fin" type="date" class="mt-1 w-full rounded-lg border border-slate-300 px-3 py-2.5">
                    </label>
                    <p class="text-sm text-slate-500 md:col-span-2">Lors d'un changement de site, l'affectation pr&eacute;c&eacute;dente sera cl&ocirc;tur&eacute;e la veille de la nouvelle date de d&eacute;but.</p>
                    <div class="md:col-span-2">
                        <button type="submit" class="rounded-xl bg-primary px-5 py-3 font-bold text-white shadow hover:bg-blue-800">Enregistrer l'affectation</button>
                    </div>
                </form>
                <p id="affectationStatus" role="status" aria-live="polite" class="mt-4 hidden rounded-lg p-3 text-sm"></p>
            </section>
        @endif

        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-6 py-5">
                <h2 class="text-xl font-bold">Historique des affectations</h2>
                <p class="mt-1 text-sm text-slate-500">{{ $affectations->count() }} affectation(s) enregistr&eacute;e(s)</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full min-w-[760px] text-left">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wide text-slate-500">
                        <tr>
                            <th class="px-6 py-4">Bien</th>
                            <th class="px-6 py-4">Site</th>
                            <th class="px-6 py-4">D&eacute;but</th>
                            <th class="px-6 py-4">Fin</th>
                            <th class="px-6 py-4">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($affectations as $affectation)
                            <tr>
                                <td class="px-6 py-4"><strong>{{ $affectation['bien_code'] }}</strong><span class="block text-sm text-slate-500">{{ $affectation['bien_libelle'] }}</span></td>
                                <td class="px-6 py-4"><strong>{{ $affectation['site_code'] }}</strong><span class="block text-sm text-slate-500">{{ $affectation['site_nom'] }}</span></td>
                                <td class="px-6 py-4">{{ \Illuminate\Support\Carbon::parse($affectation['date_debut'])->format('d/m/Y') }}</td>
                                <td class="px-6 py-4">{{ $affectation['date_fin'] ? \Illuminate\Support\Carbon::parse($affectation['date_fin'])->format('d/m/Y') : '—' }}</td>
                                <td class="px-6 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $affectation['date_fin'] ? 'bg-slate-100 text-slate-600' : 'bg-emerald-100 text-emerald-700' }}">{{ $affectation['date_fin'] ? 'Termin&eacute;e' : 'En cours' }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucune affectation enregistr&eacute;e.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script>
        const form = document.getElementById('affectationForm');
        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const status = document.getElementById('affectationStatus');
            button.disabled = true;
            status.classList.add('hidden');

            const payload = Object.fromEntries(new FormData(form).entries());
            delete payload._token;

            try {
                const response = await fetch('/gestion/affectations', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(payload)
                });
                const result = await response.json().catch(() => ({}));
                if (!response.ok) {
                    const firstError = result.errors ? Object.values(result.errors)[0]?.[0] : null;
                    throw new Error(firstError || result.message || 'Enregistrement impossible.');
                }
                window.location.reload();
            } catch (error) {
                status.textContent = error.message || 'Le service est momentanement indisponible.';
                status.className = 'mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700';
                button.disabled = false;
            }
        });
    </script>
</body>
</html>
