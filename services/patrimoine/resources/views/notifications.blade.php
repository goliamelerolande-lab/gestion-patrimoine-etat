<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - GPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 h-screen overflow-hidden flex">
    <aside class="w-72 bg-slate-900 text-white flex flex-col shadow-2xl z-20 relative">
        <div class="h-20 flex items-center px-8 border-b border-slate-700/50"><div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-3"><i class="fas fa-landmark"></i></div><span class="text-2xl font-bold">GPE.</span></div>
        <nav class="flex-1 px-4 py-8 space-y-3">
            <a href="/" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-pie w-8 text-center"></i> Tableau de bord</a>
            <a href="/biens" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-box w-8 text-center"></i> Gestion des Biens</a>
            <a href="/sites" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-map-marker-alt w-8 text-center"></i> Sites & Localisations</a>
            <a href="/rapports" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-chart-line w-8 text-center"></i> Rapports Globaux</a>
            <a href="/notifications" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl border border-blue-500/20"><i class="fas fa-bell w-8 text-center"></i> Notifications</a>
            <a href="/utilisateurs" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 rounded-xl"><i class="fas fa-users w-8 text-center"></i> Utilisateurs</a>
        </nav>
    </aside>
    <main class="flex-1 overflow-y-auto bg-white/80 p-6 md:p-10">
        <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
            <div><h1 class="text-3xl font-bold">Historique des notifications</h1><p class="mt-1 text-slate-500">Les notifications consultées restent disponibles ici.</p></div>
            <a href="/" class="rounded-xl bg-primary px-5 py-3 font-bold text-white hover:bg-emerald-800"><i class="fas fa-arrow-left mr-2"></i>Retour au tableau de bord</a>
        </div>
        <div class="overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-lg divide-y divide-slate-100">
            @forelse($notifications as $notification)
                <article class="flex items-start gap-4 p-5">
                    <span class="mt-1 flex h-10 w-10 shrink-0 items-center justify-center rounded-full {{ $notification->read_at ? 'bg-slate-100 text-slate-500' : 'bg-blue-100 text-blue-700' }}"><i class="fas {{ $notification->type === 'bien_reformed' ? 'fa-triangle-exclamation' : 'fa-bell' }}"></i></span>
                    <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center justify-between gap-2"><h2 class="font-bold text-slate-800">{{ $notification->title }}</h2><time class="text-xs text-slate-500">{{ $notification->created_at->format('d/m/Y H:i') }}</time></div><p class="mt-1 text-sm text-slate-600">{{ $notification->message }}</p></div>
                    <span class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $notification->read_at ? 'bg-slate-100 text-slate-600' : 'bg-blue-100 text-blue-700' }}">{{ $notification->read_at ? 'Lue' : 'Non lue' }}</span>
                </article>
            @empty
                <p class="p-8 text-center text-slate-500">Aucune notification enregistrée.</p>
            @endforelse
        </div>
        <div class="mt-6">{{ $notifications->links() }}</div>
    </main>
</body>
</html>
