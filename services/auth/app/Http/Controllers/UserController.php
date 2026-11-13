<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'role', 'is_active']);

        return response()->json($users);
    }

    public function store(Request $request): JsonResponse
    {
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'role' => 'required|in:admin,agent',
            'password' => 'required|string|min:12|confirmed|max:200',
        ]);

        if (User::query()->where('email', $validated['email'])->exists()) {
            return response()->json(['message' => 'Cette adresse email est déjà utilisée.'], 422);
        }

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => strtolower($validated['email']),
            'role' => $validated['role'],
            'password' => Hash::make($validated['password']),
            'is_active' => true,
        ]);

        return response()->json($user->only(['id', 'name', 'email', 'role', 'is_active']), 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::query()->findOrFail($id);
        $request->merge(['email' => strtolower(trim((string) $request->input('email')))]);
        $validated = $request->validate([
            'name' => 'required|string|max:120',
            'email' => 'required|email|max:150',
            'role' => 'required|in:admin,agent',
            'password' => 'nullable|string|min:12|confirmed|max:200',
        ]);

        if (User::query()->where('email', $validated['email'])->where('id', '<>', $user->id)->exists()) {
            return response()->json(['message' => 'Cette adresse email est déjà utilisée.'], 422);
        }

        if ($id === (int) $request->user()->id && $validated['role'] !== 'admin') {
            return response()->json(['message' => 'Vous ne pouvez pas retirer votre propre rôle administrateur.'], 422);
        }

        $result = DB::transaction(function () use ($id, $validated): User|false {
            DB::select('SELECT pg_advisory_xact_lock(477204205)');
            $user = User::query()->whereKey($id)->lockForUpdate()->firstOrFail();

            if ($user->is_active && $user->role === 'admin' && $validated['role'] !== 'admin') {
                $activeAdmins = User::query()
                    ->where('role', 'admin')
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->get(['id']);

                if ($activeAdmins->count() <= 1) {
                    return false;
                }
            }

            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
            ]);

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
                DB::table('auth.api_tokens')->where('user_id', $user->id)->delete();
            }

            $user->save();

            return $user->fresh();
        });

        if (! $result) {
            return response()->json(['message' => 'Impossible de retirer le rôle du dernier administrateur actif.'], 422);
        }

        return response()->json($result->only(['id', 'name', 'email', 'role', 'is_active']));
    }

    public function toggleStatus(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate(['is_active' => 'required|boolean']);
        $isActive = (bool) $validated['is_active'];

        if (! $isActive && $id === (int) $request->user()->id) {
            return response()->json(['message' => 'Vous ne pouvez pas désactiver votre propre compte.'], 422);
        }
        $result = DB::transaction(function () use ($id, $isActive): User|false {
            DB::select('SELECT pg_advisory_xact_lock(477204205)');
            $user = User::query()->whereKey($id)->lockForUpdate()->firstOrFail();

            if (! $isActive && $user->is_active && $user->role === 'admin') {
                $activeAdmins = User::query()
                    ->where('role', 'admin')
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->get(['id']);

                if ($activeAdmins->count() <= 1) {
                    return false;
                }
            }

            $user->is_active = $isActive;
            $user->save();

            if (! $isActive) {
                DB::table('auth.api_tokens')->where('user_id', $user->id)->delete();
            }

            return $user->fresh();
        });

        if (! $result) {
            return response()->json(['message' => 'Impossible de désactiver le dernier administrateur actif.'], 422);
        }

        return response()->json($result->only(['id', 'name', 'email', 'role', 'is_active']));
    }
}
