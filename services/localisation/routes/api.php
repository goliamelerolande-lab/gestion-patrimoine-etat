<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiteController;
use App\Http\Controllers\AffectationController;

Route::get('/sites', [SiteController::class, 'index']);
Route::post('/sites', [SiteController::class, 'store']);

Route::get('/affectations', [AffectationController::class, 'index']);
Route::post('/affectations', [AffectationController::class, 'store']);