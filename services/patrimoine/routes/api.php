<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BienController;

// Les 5 routes du CRUD (Create, Read, Update, Delete)
Route::get('/biens', [BienController::class, 'index']);        // Liste
Route::post('/biens', [BienController::class, 'store']);       // Créer
Route::get('/biens/{id}', [BienController::class, 'show']);    // Voir un
Route::put('/biens/{id}', [BienController::class, 'update']);  // Modifier
Route::delete('/biens/{id}', [BienController::class, 'destroy']); // Supprimer