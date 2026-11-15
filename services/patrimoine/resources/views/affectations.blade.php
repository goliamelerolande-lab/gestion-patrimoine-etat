<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Affectations - GPE</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: { primary: '#1e40af', dark: '#0f172a' },
                    animation: {
                        'wave': 'wave 8s ease-in-out infinite',
                    },
                    keyframes: {
                        wave: {
                            '0%, 100%': { transform: 'translate(0, 0) scale(1) rotate(0deg)' },
                            '33%': { transform: 'translate(-20px, -15px) scale(1.05) rotate(1deg)' },
                            '66%': { transform: 'translate(20px, 15px) scale(1.05) rotate(-1deg)' },
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="bg-slate-50 font-sans text-slate-800 antialiased h-screen overflow-hidden flex relative">

    <!-- ✨ ARRIÈRE-PLAN ANIMÉ ✨ -->
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-slate-100">
        <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/6/68/Flag_of_Togo.svg')] bg-[position:right_center] bg-no-repeat bg-[length:100%] animate-wave opacity-30 filter blur-[80px]"></div>
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
            <a href="/biens" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-box w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Gestion des Biens</span>
            </a>
            <a href="/sites" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-map-marker-alt w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Sites & Localisations</span>
            </a>
            <a href="/affectations" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl transition-all border border-blue-500/20">
                <i class="fas fa-exchange-alt w-8 text-center mr-3 text-lg"></i>
                <span class="font-semibold text-lg">Affectations</span>
            </a>
            <a href="/rapports" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-chart-line w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Rapports Globaux</span>
            </a>
            <a href="/utilisateurs" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
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
    <main class="flex-1 flex flex-col h-full relative z-10 overflow-hidden bg-slate-50/40 backdrop-blur-sm">
        
        <!-- Topbar -->
        <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-10 z-10 sticky top-0 shadow-sm">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Gestion des Affectations</h1>
                <p class="text-base text-slate-500 mt-1 font-medium">Attribuer un bien à un site et suivre son historique</p>
            </div>
            <a href="/" class="bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 px-5 py-2.5 rounded-xl font-bold transition flex items-center shadow-sm">
                <i class="fas fa-arrow-left mr-2"></i> Retour
            </a>
        </header>

        <!-- Contenu Scrollable -->
        <div class="flex-1 overflow-y-auto p-10">
            
            @if(empty($biens) || $sites->isEmpty())
                <div class="bg-amber-50 border border-amber-200 text-amber-900 p-6 rounded-2xl mb-8 flex items-center shadow-sm">
                    <i class="fas fa-exclamation-triangle text-2xl mr-4"></i>
                    <div>
                        <p class="font-bold">Attention</p>
                        <p class="text-sm">Vous devez d'abord créer au moins un bien et un site avant de pouvoir enregistrer une affectation.</p>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Formulaire de création -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl shadow-slate-200/50 border border-white/50 p-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-plus-circle text-blue-500 mr-3"></i> Nouvelle Affectation
                    </h2>
                    
                    <form id="affectationForm" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sélectionner le Bien</label>
                            <select name="bien_id" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="">-- Choisir un bien --</option>
                                @foreach($biens as $bien)
                                    <option value="{{ $bien->id }}">{{ $bien->code_inventaire }} - {{ $bien->libelle }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Sélectionner le Site</label>
                            <select name="site_id" required class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none bg-white">
                                <option value="">-- Choisir un site --</option>
                                @foreach($sites as $site)
                                    <option value="{{ $site['id'] }}">{{ $site['code'] }} - {{ $site['nom'] }} ({{ $site['ville'] }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Date de début</label>
                                <input type="date" name="date_debut" required value="{{ now()->toDateString() }}" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Date de fin (optionnel)</label>
                                <input type="date" name="date_fin" class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full bg-primary hover:bg-blue-800 text-white px-6 py-4 rounded-xl text-lg font-bold transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center">
                                <i class="fas fa-check mr-2"></i> Enregistrer l'affectation
                            </button>
                        </div>
                        <p id="affectationStatus" role="status" aria-live="polite" class="mt-2 hidden rounded-lg p-3 text-sm text-center"></p>
                    </form>
                </div>

                <!-- Historique -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl shadow-slate-200/50 border border-white/50 p-8">
                    <h2 class="text-xl font-bold text-slate-800 mb-6 flex items-center">
                        <i class="fas fa-history text-amber-500 mr-3"></i> Historique ({{ $affectations->count() }})
                    </h2>
                    
                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @forelse($affectations as $affectation)
                            <div class="p-4 bg-white rounded-xl border border-slate-200 shadow-sm flex justify-between items-start">
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $affectation['bien_code'] }} - {{ $affectation['bien_libelle'] }}</p>
                                    <p class="text-sm text-slate-600 mt-1"><i class="fas fa-map-marker-alt text-blue-500 mr-1"></i> {{ $affectation['site_nom'] }} ({{ $affectation['site_code'] }})</p>
                                    <p class="text-xs text-slate-400 mt-2">
                                        Début : {{ \Carbon\Carbon::parse($affectation['date_debut'])->format('d/m/Y') }}
                                        @if($affectation['date_fin'])
                                            — Fin : {{ \Carbon\Carbon::parse($affectation['date_fin'])->format('d/m/Y') }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-xs font-bold px-3 py-1 rounded-full {{ $affectation['date_fin'] ? 'bg-slate-100 text-slate-600' : 'bg-emerald-100 text-emerald-800' }}">
                                    {{ $affectation['date_fin'] ? 'Terminé' : 'Actif' }}
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-10 text-slate-500">
                                <i class="fas fa-inbox text-4xl mb-3 text-slate-300"></i>
                                <p>Aucune affectation enregistrée.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- SCRIPT (Adapté à ton web.php existant) -->
    <script>
        const form = document.getElementById('affectationForm');
        const status = document.getElementById('affectationStatus');

        form?.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
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
                status.textContent = error.message || 'Le service est momentanément indisponible.';
                status.className = 'mt-4 rounded-lg bg-red-50 p-3 text-sm text-red-700 text-center';
                status.classList.remove('hidden');
                button.disabled = false;
            }
        });
    </script>
</body>
</html>