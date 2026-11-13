<?php

use Illuminate\Support\Facades\Route;
use App\Models\Bien;
use Illuminate\Support\Facades\Http;

Route::get('/', function () {
    $biens = Bien::all();
    return view('dashboard', compact('biens'));
});

Route::get('/rapports', function () {
    $biens = Bien::all();
    return view('rapports', compact('biens'));
});

Route::get('/biens', function () {
    $biens = Bien::all();
    return view('biens', compact('biens'));
});

Route::get('/sites', function () {
    $sites = [];
    try {
        // On tente de contacter le microservice localisation
        $response = Http::timeout(3)->get('http://localisation-service:80/api/sites');
        if ($response->successful()) {
            $sites = $response->json();
        }
    } catch (\Exception $e) {
        $sites = []; // Si le service est éteint, on affiche une liste vide
    }
    return view('sites', compact('sites'));
});

Route::get('/utilisateurs', function () {
    $users = [
        ['id' => 1, 'name' => 'Administrateur', 'email' => 'admin@etat.gouv', 'role' => 'admin'],
    ];
    return view('utilisateurs', compact('users'));
});