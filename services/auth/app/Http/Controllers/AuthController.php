<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if (! $user || ! Hash::check($validated['password'], $user->password) || $user->role !== 'admin' || ! $user->is_active) {
            return response()->json(['message' => 'Identifiants invalides'], 401);
        }

        $plainTextToken = Str::random(64);
        $expiresAt = now()->addHours(8);
        DB::table('auth.api_tokens')->insert([
            'user_id' => $user->id,
            'token_hash' => hash('sha256', $plainTextToken),
            'expires_at' => $expiresAt,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'token' => $plainTextToken,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }
}