<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
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
        </nav>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 p-10 overflow-y-auto bg-white/80">
        <h1 class="text-3xl font-bold mb-8">Gestion des Utilisateurs</h1>
        <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden">
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 font-bold">Nom</th>
                        <th class="px-6 py-4 font-bold">Email</th>
                        <th class="px-6 py-4 font-bold">Rôle</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-bold text-slate-700">{{ $user['name'] }}</td>
                        <td class="px-6 py-4">{{ $user['email'] }}</td>
                        <td class="px-6 py-4"><span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-xs font-bold">{{ ucfirst($user['role']) }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
