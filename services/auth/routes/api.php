<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

Route::middleware('admin.token')->group(function (): void {
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::patch('/users/{id}/status', [UserController::class, 'toggleStatus']);

    Route::get('/me', function (Request $request) {
        return response()->json($request->user()->only(['id', 'name', 'email', 'role']));
    });

    Route::post('/logout', function (Request $request) {
        DB::table('auth.api_tokens')
            ->where('id', $request->attributes->get('api_token_id'))
            ->delete();

        return response()->noContent();
    });
});
