<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $plainTextToken = $request->bearerToken();

        if (! $plainTextToken) {
            return response()->json(['message' => 'Authentification requise.'], 401);
        }

        $token = DB::table('auth.api_tokens')
            ->where('token_hash', hash('sha256', $plainTextToken))
            ->where('expires_at', '>', now())
            ->first();

        $user = $token ? User::find($token->user_id) : null;

        if (! $token || ! $user || $user->role !== 'admin' || ! $user->is_active) {
            return response()->json(['message' => 'Session invalide ou accès administrateur requis.'], 401);
        }

        DB::table('auth.api_tokens')->where('id', $token->id)->update(['last_used_at' => now()]);
        $request->attributes->set('api_token_id', $token->id);
        $request->setUserResolver(fn (): User => $user);

        return $next($request);
    }
}
