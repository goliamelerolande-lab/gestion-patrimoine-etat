<?php

<<<<<<< HEAD
use App\Http\Controllers\AuthSessionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Middleware\EnsureAdminSession;
use App\Models\Bien;
use App\Models\Notification;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
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

    $notifications = Notification::query()
        ->whereNull('read_at')
        ->latest()
        ->limit(5)
        ->get(['id', 'type', 'title', 'message', 'created_at']);
    $unreadCount = Notification::query()->whereNull('read_at')->count();

    return view('dashboard', compact('stats', 'biens', 'byState', 'notifications', 'unreadCount'));
})->name('dashboard');

Route::get('/notifications', function () {
    $notifications = Notification::query()
        ->latest()
        ->paginate(20, ['id', 'type', 'title', 'message', 'read_at', 'created_at']);
    $unreadCount = Notification::query()->whereNull('read_at')->count();

    return view('notifications', compact('notifications', 'unreadCount'));
})->name('notifications.index');

Route::post('/notifications/mark-viewed', function () {
    $marked = Notification::query()->whereNull('read_at')->update(['read_at' => now()]);

    if (request()->expectsJson()) {
        return response()->json(['marked' => $marked]);
    }

    return redirect()->route('notifications.index')->with('status', 'Notifications marquées comme lues.');
})->name('notifications.mark-viewed');

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
    if (request()->boolean('fresh')) {
        Cache::forget('localisation.sites');
    }

    $sites = Cache::remember('localisation.sites', now()->addSeconds(30), function () {
        try {
            $response = Http::connectTimeout(3)
                ->timeout(15)
                ->get('http://localisation-service:80/api/sites');

            return $response->successful() ? ($response->json() ?? []) : [];
        } catch (Throwable) {
            return [];
        }
    });

    return view('sites', compact('sites'));
})->name('sites.index');

Route::get('/affectations', function () {
    if (! session()->has('admin_user.id')) {
        return redirect()->route('login')->withErrors([
            'email' => 'Connectez-vous comme administrateur pour enregistrer une affectation.',
        ]);
    }

    $biens = Bien::query()
        ->select(['id', 'code_inventaire', 'libelle'])
        ->orderBy('code_inventaire')
        ->get();

    try {
        $sitesResponse = Http::connectTimeout(3)
            ->timeout(15)
            ->get(rtrim(config('services.localisation.url'), '/').'/api/sites');
        $affectationsResponse = Http::connectTimeout(3)
            ->timeout(15)
            ->get(rtrim(config('services.localisation.url'), '/').'/api/affectations');
    } catch (Throwable $e) {
        $sitesResponse = null;
        $affectationsResponse = null;
    }

    if (!$sitesResponse || !$sitesResponse->successful() || !$affectationsResponse || !$affectationsResponse->successful()) {
        $sites = collect([]);
        $affectations = collect([]);
    } else {
        $sites = collect($sitesResponse->json() ?? [])->keyBy('id');
        $biensById = $biens->keyBy('id');
        $affectations = collect($affectationsResponse->json() ?? [])
            ->map(function (array $affectation) use ($sites, $biensById): array {
                $site = $sites->get($affectation['site_id']);
                $bien = $biensById->get($affectation['bien_id']);
                $affectation['site_code'] = $site['code'] ?? 'Site supprime';
                $affectation['site_nom'] = $site['nom'] ?? 'Site supprime';
                $affectation['bien_code'] = $bien?->code_inventaire ?? 'Bien supprime';
                $affectation['bien_libelle'] = $bien?->libelle ?? 'Bien supprime';

                return $affectation;
            })
            ->sortByDesc('date_debut')
            ->values();
    }

    $currentUserId = (int) session('admin_user.id');
    $selectedBienId = (int) request('bien_id');

    return view('affectations', compact('biens', 'sites', 'affectations', 'currentUserId', 'selectedBienId'));
})->middleware(EnsureAdminSession::class)->name('affectations.index');

Route::middleware(EnsureAdminSession::class)->post('/gestion/affectations', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'bien_id' => 'required|integer',
        'site_id' => 'required|integer',
        'date_debut' => 'required|date',
        'date_fin' => 'nullable|date|after_or_equal:date_debut',
    ]);

    DB::table('localisation.affectations')->insert([
        'bien_id' => $validated['bien_id'],
        'site_id' => $validated['site_id'],
        'user_id' => session('admin_user.id'),
        'date_debut' => $validated['date_debut'],
        'date_fin' => $validated['date_fin'] ?? null,
    ]);

    if ($request->expectsJson()) {
        return response()->json(['message' => 'Affectation enregistrée avec succès.']);
    }

    return redirect()->route('affectations.index')->with('status', 'Affectation enregistrée avec succès.');
})->name('affectations.store');

Route::get('/connexion', [AuthSessionController::class, 'create'])->name('login');
Route::post('/connexion', [AuthSessionController::class, 'store'])->name('login.store');
Route::post('/deconnexion', [AuthSessionController::class, 'destroy'])->middleware(EnsureAdminSession::class)->name('logout');

Route::get('/utilisateurs', function () {
    $token = session('admin_token');
    $response = Http::connectTimeout(1)
        ->timeout(3)
        ->withToken($token)
        ->acceptJson()
        ->get(rtrim(config('services.auth.url'), '/').'/api/users');

    abort_unless($response->successful(), 503, 'Le registre des comptes est momentanément indisponible.');

    $users = $response->json();

    return view('utilisateurs', compact('users'));
})->middleware(EnsureAdminSession::class)->name('users.index');

Route::middleware(EnsureAdminSession::class)->prefix('/gestion/utilisateurs')->name('users.')->group(function (): void {
    Route::get('/', [UserManagementController::class, 'index'])->name('data');
    Route::post('/', [UserManagementController::class, 'store'])->name('store');
    Route::put('/{id}', [UserManagementController::class, 'update'])->whereNumber('id')->name('update');
    Route::patch('/{id}/statut', [UserManagementController::class, 'setStatus'])->whereNumber('id')->name('status');
});
=======
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
