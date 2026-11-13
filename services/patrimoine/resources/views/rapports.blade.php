<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapports - GPE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased h-screen overflow-hidden flex relative">

    <!-- ✨ ARRIÈRE-PLAN ANIMÉ ✨ -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-50">
        <div class="absolute inset-0 bg-[url('/images/togo-flag.svg')] bg-[position:right_top] bg-no-repeat bg-[length:70%] animate-wave opacity-10"></div>
    </div>

    <!-- SIDEBAR -->
    <aside class="w-72 bg-dark text-white flex flex-col shadow-2xl z-20 relative">
        <div class="h-20 flex items-center px-8 border-b border-slate-700/50 bg-dark">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                <i class="fas fa-landmark text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold tracking-wide">GPE<span class="text-blue-500">.</span></span>
        </div>
        
        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
            <p class="px-4 text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Menu Principal</p>
            <a href="/" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-chart-pie w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Tableau de bord</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-box w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Gestion des Biens</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-map-marker-alt w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Sites & Localisations</span>
            </a>
            <!-- Lien Rapports ACTIF -->
            <a href="/rapports" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl transition-all border border-blue-500/20">
                <i class="fas fa-chart-line w-8 text-center mr-3 text-lg"></i>
                <span class="font-semibold text-lg">Rapports Globaux</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-users w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Utilisateurs</span>
            </a>
        </nav>

        <div class="p-6 border-t border-slate-700/50 bg-dark">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-tr from-blue-500 to-indigo-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg text-lg">A</div>
                <div class="ml-4">
                    <p class="text-base font-bold text-white">Administrateur</p>
                    <p class="text-sm text-slate-400">admin@etat.gouv</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full relative z-10 overflow-hidden bg-white/80">
        
        <!-- Topbar -->
        <header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-10 z-10 sticky top-0 shadow-sm">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Rapports Globaux</h1>
                <p class="text-base text-slate-500 mt-1 font-medium">Analyse détaillée et exportation des données</p>
            </div>
            <div class="flex items-center space-x-4">
                <button class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
                    <i class="fas fa-file-pdf text-red-500 mr-2"></i> Exporter PDF
                </button>
                <a href="/" class="bg-primary hover:bg-blue-800 text-white px-5 py-2.5 rounded-xl font-bold transition shadow-lg shadow-blue-500/30 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </a>
            </div>
        </header>

        <!-- Contenu Scrollable -->
        <div class="flex-1 overflow-y-auto p-10">
            
            <!-- Cartes de Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl p-8 shadow-xl shadow-blue-500/20 text-white relative overflow-hidden group">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4">
                        <i class="fas fa-boxes text-9xl"></i>
                    </div>
                    <p class="text-blue-100 font-semibold text-lg mb-2">Total des Biens</p>
                    <h2 class="text-5xl font-extrabold">{{ number_format((int) $stats->total_biens, 0, ',', ' ') }}</h2>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100 relative overflow-hidden group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 font-semibold text-lg mb-2">Valeur Cumulée</p>
                            <h2 class="text-4xl font-extrabold text-slate-800">{{ number_format($stats->valeur_totale, 0, ',', ' ') }} <span class="text-xl text-slate-500">FCFA</span></h2>
                        </div>
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-2xl p-8 shadow-lg border border-slate-100 relative overflow-hidden group">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 font-semibold text-lg mb-2">Date du rapport</p>
                            <h2 class="text-3xl font-extrabold text-slate-800">{{ date('d/m/Y') }}</h2>
                        </div>
                        <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-calendar-alt text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tableau Détaillé -->
            <div class="bg-white rounded-2xl shadow-lg border border-slate-100 overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800">Derniers biens enregistrés</h2>
                    <p class="text-base text-slate-500 mt-1">Les 20 biens les plus récents du registre</p>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 text-slate-500 text-sm uppercase tracking-wider">
                                <th class="px-8 py-5 font-bold">Code Inventaire</th>
                                <th class="px-8 py-5 font-bold">Libellé</th>
                                <th class="px-8 py-5 font-bold">Date d'acquisition</th>
                                <th class="px-8 py-5 font-bold">Valeur</th>
                                <th class="px-8 py-5 font-bold">État</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($biens as $bien)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-6 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 bg-slate-100 px-4 py-2 rounded-lg text-base border border-slate-200">{{ $bien->code_inventaire }}</span>
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap font-bold text-slate-700 text-lg">{{ $bien->libelle }}</td>
                                <td class="px-8 py-6 whitespace-nowrap text-slate-500 font-medium">
                                    {{ \Carbon\Carbon::parse($bien->date_acquisition)->format('d/m/Y') }}
                                </td>
                                <td class="px-8 py-6 whitespace-nowrap text-emerald-600 font-bold text-lg">{{ number_format($bien->valeur_acquisition, 0, ',', ' ') }} F</td>
                                <td class="px-8 py-6 whitespace-nowrap">
                                    @if($bien->etat == 'neuf')
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">Neuf</span>
                                    @elseif($bien->etat == 'usage')
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-100 text-amber-800">En usage</span>
                                    @else
                                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-slate-100 text-slate-800">{{ ucfirst($bien->etat) }}</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>

</body>
</html>
