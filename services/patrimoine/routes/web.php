<?php

use App\Models\Bien;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $stats = Bien::query()
        ->selectRaw('COUNT(*) as total_biens')
        ->selectRaw('COALESCE(SUM(valeur_acquisition), 0) as valeur_totale')
        ->selectRaw("COUNT(*) FILTER (WHERE etat = 'usage') as biens_usage")
        ->first();

    $biens = Bien::query()
        ->select(['id', 'code_inventaire', 'libelle', 'date_acquisition', 'valeur_acquisition', 'etat'])
        ->orderByDesc('id')
        ->limit(6)
        ->get();

    $byState = Bien::query()
        ->select('etat')
        ->selectRaw('COUNT(*) as total')
        ->groupBy('etat')
        ->orderBy('etat')
        ->get();

    $notifications = collect();
    $recentCount = Bien::query()->where('created_at', '>=', now()->subDays(7))->count();
    $reformCount = Bien::query()->where('etat', 'reforme')->count();

    if ($recentCount > 0) {
        $notifications->push([
            'title' => $recentCount . ' bien(s) ajouté(s)',
            'description' => 'Nouveaux enregistrements sur les 7 derniers jours.',
            'type' => 'success',
        ]);
    }

    if ($reformCount > 0) {
        $notifications->push([
            'title' => $reformCount . ' bien(s) réformé(s)',
            'description' => 'À vérifier dans le registre du patrimoine.',
            'type' => 'warning',
        ]);
    }

    return view('dashboard', compact('stats', 'biens', 'byState', 'notifications'));
})->name('dashboard');

Route::get('/rapports', function () {
    $stats = Bien::query()
        ->selectRaw('COUNT(*) as total_biens')
        ->selectRaw('COALESCE(SUM(valeur_acquisition), 0) as valeur_totale')
        ->selectRaw("COUNT(*) FILTER (WHERE etat = 'usage') as biens_usage")
        ->first();

    $byState = Bien::query()
        ->select('etat')
        ->selectRaw('COUNT(*) as total')
        ->selectRaw('COALESCE(SUM(valeur_acquisition), 0) as valeur_totale')
        ->groupBy('etat')
        ->orderBy('etat')
        ->get();

    $biens = Bien::query()
        ->select(['id', 'code_inventaire', 'libelle', 'date_acquisition', 'valeur_acquisition', 'etat'])
        ->orderByDesc('id')
        ->limit(20)
        ->get();

    return view('rapports', compact('stats', 'byState', 'biens'));
})->name('reports');

Route::get('/biens', function () {
    $biens = Bien::query()
        ->select(['id', 'code_inventaire', 'libelle', 'date_acquisition', 'valeur_acquisition', 'etat'])
        ->orderByDesc('id')
        ->paginate(12)
        ->withQueryString();

    return view('biens', compact('biens'));
})->name('biens.index');

Route::get('/sites', function () {
    $sites = Cache::remember('localisation.sites', now()->addSeconds(30), function () {
        try {
            $response = Http::connectTimeout(1)
                ->timeout(2)
                ->get('http://localisation-service:80/api/sites');

            return $response->successful() ? ($response->json() ?? []) : [];
        } catch (\Throwable) {
            return [];
        }
    });

    return view('sites', compact('sites'));
})->name('sites.index');

Route::get('/utilisateurs', function () {
    $users = [
        ['id' => 1, 'name' => 'Administrateur', 'email' => 'admin@etat.gouv', 'role' => 'admin'],
    ];

    return view('utilisateurs', compact('users'));
})->name('users.index');
