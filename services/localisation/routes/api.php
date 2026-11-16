<?php

use App\Http\Controllers\AffectationController;
use App\Http\Controllers\SiteController;
use Illuminate\Support\Facades\Route;

Route::get('/sites', [SiteController::class, 'index']);
Route::post('/sites', [SiteController::class, 'store']);
Route::get('/sites/{id}', [SiteController::class, 'show']);
Route::put('/sites/{id}', [SiteController::class, 'update']);
Route::patch('/sites/{id}', [SiteController::class, 'update']);
Route::delete('/sites/{id}', [SiteController::class, 'destroy']);

Route::get('/affectations', [AffectationController::class, 'index']);
Route::post('/affectations', [AffectationController::class, 'store']);