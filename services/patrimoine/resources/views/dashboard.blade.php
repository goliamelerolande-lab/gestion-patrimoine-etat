<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GPE - Gestion du Patrimoine de l'État</title>
    
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
                        'wave': 'wave 7s ease-in-out infinite',
                        'pulse-slow': 'pulse 6s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'pulse-slower': 'pulse 8s cubic-bezier(0.4, 0, 0.6, 1) infinite reverse',
                    },
                    keyframes: {
                        wave: {
                            '0%, 100%': { transform: 'translate(0, 0) scale(1) rotate(0deg) skewX(0deg) skewY(0deg)', opacity: '0.15' },
                            '25%': { transform: 'translate(-30px, -20px) scale(1.15) rotate(-3deg) skewX(8deg) skewY(-5deg)', opacity: '0.25' },
                            '50%': { transform: 'translate(20px, 30px) scale(1.05) rotate(3deg) skewX(-8deg) skewY(5deg)', opacity: '0.20' },
                            '75%': { transform: 'translate(30px, -20px) scale(1.1) rotate(2deg) skewX(5deg) skewY(-3deg)', opacity: '0.25' },
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
        <div class="absolute inset-0 bg-[url('https://upload.wikimedia.org/wikipedia/commons/6/68/Flag_of_Togo.svg')] bg-[position:right_top] bg-no-repeat bg-[length:80%] animate-wave filter blur-[1px]"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-emerald-400 rounded-full mix-blend-multiply filter blur-[120px] animate-pulse-slow"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-yellow-400 rounded-full mix-blend-multiply filter blur-[120px] animate-pulse-slower"></div>
    </div>

    <!-- SIDEBAR -->
    <aside class="w-72 bg-dark text-white flex flex-col shadow-2xl z-20 relative">
        <div class="h-20 flex items-center px-8 border-b border-slate-700/50 bg-dark/95 backdrop-blur-sm">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center mr-3 shadow-lg shadow-blue-500/30">
                <i class="fas fa-landmark text-white text-lg"></i>
            </div>
            <span class="text-2xl font-bold tracking-wide">GPE<span class="text-blue-500">.</span></span>
        </div>
        
        <nav class="flex-1 px-4 py-8 space-y-3 overflow-y-auto">
            <p class="px-4 text-sm font-bold text-slate-400 uppercase tracking-wider mb-3">Menu Principal</p>
            <a href="/" class="flex items-center px-4 py-3.5 bg-blue-600/10 text-blue-400 rounded-xl transition-all border border-blue-500/20">
                <i class="fas fa-chart-pie w-8 text-center mr-3 text-lg"></i>
                <span class="font-semibold text-lg">Tableau de bord</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-box w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Gestion des Biens</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-map-marker-alt w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Sites & Localisations</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3.5 text-slate-400 hover:bg-slate-800 hover:text-white rounded-xl transition-all">
                <i class="fas fa-users w-8 text-center mr-3 text-lg"></i>
                <span class="font-medium text-lg">Utilisateurs</span>
            </a>
        </nav>

        <div class="p-6 border-t border-slate-700/50 bg-dark/95 backdrop-blur-sm">
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
    <main class="flex-1 flex flex-col h-full relative z-10 overflow-hidden bg-gradient-to-r from-white/95 via-white/70 to-transparent backdrop-blur-sm">
        
        <!-- Topbar -->
        <header class="h-20 bg-white/80 backdrop-blur-xl border-b border-slate-200 flex items-center justify-between px-10 z-10 sticky top-0 shadow-sm">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Vue d'ensemble</h1>
                <p class="text-base text-slate-500 mt-1 font-medium">Bienvenue sur votre espace de gestion du patrimoine</p>
            </div>
            <div class="flex items-center space-x-8">
                <button class="relative text-slate-400 hover:text-slate-600 transition">
                    <i class="fas fa-bell text-2xl"></i>
                    <span class="absolute -top-1 -right-1 w-3.5 h-3.5 bg-red-500 rounded-full border-2 border-white animate-pulse"></span>
                </button>
                <div class="h-10 w-px bg-slate-200"></div>
                <button class="flex items-center text-base font-bold text-slate-600 hover:text-slate-900 transition">
                    <i class="fas fa-sign-out-alt mr-2 text-lg"></i> Déconnexion
                </button>
            </div>
        </header>

        <!-- Contenu Scrollable -->
        <div class="flex-1 overflow-y-auto p-10">
            
            <!-- Cartes de Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div class="bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl p-8 shadow-xl shadow-blue-500/20 text-white relative overflow-hidden group">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-4 -translate-y-4 group-hover:scale-110 transition-transform duration-500">
                        <i class="fas fa-boxes text-9xl"></i>
                    </div>
                    <p class="text-blue-100 font-semibold text-lg mb-2">Total des Biens</p>
                    <h2 class="text-5xl font-extrabold">{{ count($biens) }}</h2>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-white/50 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-emerald-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 font-semibold text-lg mb-2">Valeur Totale (FCFA)</p>
                            <h2 class="text-4xl font-extrabold text-slate-800">{{ number_format($biens->sum('valeur_acquisition'), 0, ',', ' ') }}</h2>
                        </div>
                        <div class="w-14 h-14 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-money-bill-wave text-2xl"></i>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 backdrop-blur-xl rounded-2xl p-8 shadow-xl shadow-slate-200/50 border border-white/50 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-32 h-32 bg-amber-50 rounded-bl-full -z-10 group-hover:scale-110 transition-transform"></div>
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-slate-500 font-semibold text-lg mb-2">Biens en usage</p>
                            <h2 class="text-4xl font-extrabold text-slate-800">{{ $biens->where('etat', 'usage')->count() }}</h2>
                        </div>
                        <div class="w-14 h-14 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center shadow-inner">
                            <i class="fas fa-tools text-2xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grille principale -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Tableau des Biens -->
                <div class="lg:col-span-2 bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl shadow-slate-200/50 border border-white/50 overflow-hidden flex flex-col">
                    <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center bg-white/50">
                        <div>
                            <h2 class="text-xl font-bold text-slate-800">Derniers Biens Enregistrés</h2>
                            <p class="text-base text-slate-500 mt-1">Liste des actifs récemment ajoutés</p>
                        </div>
                        <button onclick="document.getElementById('modalNouveauBien').classList.remove('hidden')" class="bg-primary hover:bg-blue-800 text-white px-6 py-3 rounded-xl text-base font-bold transition-all shadow-lg shadow-blue-500/30 flex items-center transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i> Nouveau Bien
                        </button>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/80 text-slate-500 text-sm uppercase tracking-wider">
                                    <th class="px-8 py-5 font-bold">Code</th>
                                    <th class="px-8 py-5 font-bold">Libellé</th>
                                    <th class="px-8 py-5 font-bold">Valeur</th>
                                    <th class="px-8 py-5 font-bold">État</th>
                                    <th class="px-8 py-5 font-bold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($biens as $bien)
                                <tr class="hover:bg-blue-50/50 transition-colors group">
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        <span class="font-bold text-slate-800 bg-slate-100 px-4 py-2 rounded-lg text-base border border-slate-200">{{ $bien->code_inventaire }}</span>
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap font-bold text-slate-700 text-lg">{{ $bien->libelle }}</td>
                                    <td class="px-8 py-6 whitespace-nowrap text-slate-700 font-medium text-lg">{{ number_format($bien->valeur_acquisition, 0, ',', ' ') }} F</td>
                                    <td class="px-8 py-6 whitespace-nowrap">
                                        @if($bien->etat == 'neuf')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-emerald-100 text-emerald-800">
                                                <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2"></span> Neuf
                                            </span>
                                        @elseif($bien->etat == 'usage')
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                                                <span class="w-2 h-2 bg-amber-500 rounded-full mr-2"></span> En usage
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-slate-100 text-slate-800">
                                                <span class="w-2 h-2 bg-slate-500 rounded-full mr-2"></span> {{ ucfirst($bien->etat) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <!-- Bouton Voir -->
                                            <button onclick="ouvrirModalVoir({{ Js::from($bien) }})" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition-colors" title="Voir">
                                                <i class="fas fa-eye text-xs"></i>
                                            </button>
                                            
                                            <!-- Bouton Modifier -->
                                            <button onclick="ouvrirModalModifier({{ Js::from($bien) }})" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center hover:bg-amber-600 hover:text-white transition-colors" title="Modifier">
                                                <i class="fas fa-pen text-xs"></i>
                                            </button>
                                            
                                            <!-- Bouton Supprimer -->
                                            <button onclick="supprimerBien({{ $bien->id }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center hover:bg-red-600 hover:text-white transition-colors" title="Supprimer">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class="fas fa-inbox text-6xl text-slate-200 mb-4"></i>
                                            <p class="text-lg font-medium">Aucun bien enregistré pour le moment.</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Widget Graphique -->
                <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-xl shadow-slate-200/50 border border-white/50 p-8 flex flex-col justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 mb-6">Répartition par Catégorie</h2>
                        <div class="flex justify-center items-center h-48 mb-6 relative">
                            <div class="w-40 h-40 rounded-full border-[16px] border-blue-100 flex items-center justify-center shadow-inner">
                                <span class="text-2xl font-bold text-slate-700">100%</span>
                            </div>
                        </div>
                        <div class="space-y-4 mt-4">
                            <div class="flex justify-between items-center p-2 rounded-lg hover:bg-slate-50 transition">
                                <span class="flex items-center text-base font-medium text-slate-600"><span class="w-3 h-3 bg-blue-600 rounded-full mr-3"></span> Informatique</span>
                                <span class="font-bold text-slate-800 text-lg">1</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-slate-100">
                        <a href="/rapports" class="block w-full py-3 text-center text-blue-600 font-bold text-base hover:bg-blue-50 rounded-xl transition">
                            Voir tous les rapports <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- MODAL NOUVEAU BIEN -->
    <div id="modalNouveauBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden transform transition-all">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Ajouter un nouveau bien</h3>
                <button onclick="document.getElementById('modalNouveauBien').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="formNouveauBien" class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Libellé</label>
                        <input type="text" id="new_libelle" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">État</label>
                        <select id="new_etat" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="neuf">Neuf</option>
                            <option value="usage">En usage</option>
                            <option value="reforme">Réformé</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Date d'acquisition</label>
                        <input type="date" id="new_date" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Valeur (FCFA)</label>
                        <input type="number" id="new_valeur" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalNouveauBien').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL VOIR UN BIEN -->
    <div id="modalVoirBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Détails du bien</h3>
                <button onclick="document.getElementById('modalVoirBien').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-4 text-lg">
                <p><strong class="text-slate-600">Code :</strong> <span id="voir_code" class="font-bold"></span></p>
                <p><strong class="text-slate-600">Libellé :</strong> <span id="voir_libelle" class="font-bold"></span></p>
                <p><strong class="text-slate-600">Date d'acquisition :</strong> <span id="voir_date"></span></p>
                <p><strong class="text-slate-600">Valeur :</strong> <span id="voir_valeur" class="text-emerald-600 font-bold"></span></p>
                <p><strong class="text-slate-600">État :</strong> <span id="voir_etat"></span></p>
            </div>
            <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 text-right">
                <button onclick="document.getElementById('modalVoirBien').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-white bg-slate-600 hover:bg-slate-700 transition">Fermer</button>
            </div>
        </div>
    </div>

    <!-- MODAL MODIFIER UN BIEN -->
    <div id="modalModifierBien" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg overflow-hidden">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-200 flex justify-between items-center">
                <h3 class="text-xl font-bold text-slate-800">Modifier le bien</h3>
                <button onclick="document.getElementById('modalModifierBien').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="formModifierBien" class="p-6 space-y-4">
                <input type="hidden" id="edit_id">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Libellé</label>
                    <input type="text" id="edit_libelle" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">État</label>
                        <select id="edit_etat" class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="neuf">Neuf</option>
                            <option value="usage">En usage</option>
                            <option value="reforme">Réformé</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1">Valeur (FCFA)</label>
                        <input type="number" id="edit_valeur" required class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>
                <div class="pt-4 border-t border-slate-200 flex justify-end space-x-3">
                    <button type="button" onclick="document.getElementById('modalModifierBien').classList.add('hidden')" class="px-5 py-2.5 rounded-xl font-bold text-slate-600 bg-slate-100 hover:bg-slate-200 transition">Annuler</button>
                    <button type="submit" class="px-5 py-2.5 rounded-xl font-bold text-white bg-amber-500 hover:bg-amber-600 shadow-lg shadow-amber-500/30 transition">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>

    <!-- SCRIPTS JAVASCRIPT (CRUD COMPLET) -->
    <script>
        // 1. CRÉATION (POST)
        document.getElementById('formNouveauBien').addEventListener('submit', async function(e) {
            e.preventDefault(); 
            const formData = {
                libelle: document.getElementById('new_libelle').value,
                category_id: 1,
                date_acquisition: document.getElementById('new_date').value,
                valeur_acquisition: parseFloat(document.getElementById('new_valeur').value),
                etat: document.getElementById('new_etat').value
            };

            const response = await fetch('/api/patrimoine/biens', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(formData)
            });

            if (response.ok) window.location.reload();
            else alert("Erreur lors de la création.");
        });

        // 2. VOIR (Modal de lecture)
        function ouvrirModalVoir(bien) {
            document.getElementById('voir_code').innerText = bien.code_inventaire;
            document.getElementById('voir_libelle').innerText = bien.libelle;
            document.getElementById('voir_date').innerText = bien.date_acquisition;
            document.getElementById('voir_valeur').innerText = parseInt(bien.valeur_acquisition).toLocaleString() + ' FCFA';
            document.getElementById('voir_etat').innerText = bien.etat.toUpperCase();
            document.getElementById('modalVoirBien').classList.remove('hidden');
        }

        // 3. MODIFIER (Remplir le formulaire et ouvrir)
        function ouvrirModalModifier(bien) {
            document.getElementById('edit_id').value = bien.id;
            document.getElementById('edit_libelle').value = bien.libelle;
            document.getElementById('edit_etat').value = bien.etat;
            document.getElementById('edit_valeur').value = bien.valeur_acquisition;
            document.getElementById('modalModifierBien').classList.remove('hidden');
        }

        // 3b. ENVOYER LA MODIFICATION (POST + _method PUT)
        document.getElementById('formModifierBien').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('edit_id').value;
            const formData = {
                _method: 'PUT',
                libelle: document.getElementById('edit_libelle').value,
                etat: document.getElementById('edit_etat').value,
                valeur_acquisition: parseFloat(document.getElementById('edit_valeur').value)
            };

            try {
                const response = await fetch(`/api/patrimoine/biens/${id}`, {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(formData)
                });

                if (response.ok) {
                    window.location.reload();
                } else {
                    const err = await response.json();
                    alert("Erreur : " + JSON.stringify(err));
                }
            } catch (error) {
                alert("Erreur réseau : " + error.message);
            }
        });

        // 4. SUPPRIMER (Méthode POST + _method DELETE)
        async function supprimerBien(id) {
            if (confirm("Êtes-vous sûr de vouloir supprimer définitivement ce bien ?")) {
                try {
                    const response = await fetch(`/api/patrimoine/biens/${id}`, {
                        method: 'POST', 
                        headers: { 
                            'Content-Type': 'application/json', 
                            'Accept': 'application/json' 
                        },
                        body: JSON.stringify({ _method: 'DELETE' })
                    });

                    if (response.ok) {
                        window.location.reload();
                    } else {
                        const err = await response.json();
                        alert("Erreur lors de la suppression : " + JSON.stringify(err));
                    }
                } catch (error) {
                    alert("Erreur réseau : " + error.message);
                }
            }
        }
    </script>
</body>
</html>