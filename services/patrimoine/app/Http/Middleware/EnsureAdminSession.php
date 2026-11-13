<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->session()->get('admin_token');

        if (! $token) {
            return redirect()->route('login')->withErrors([
                'email' => 'Connectez-vous avec un compte administrateur pour accéder à cette page.',
            ]);
        }

        try {
            $response = Http::connectTimeout(1)
                ->timeout(2)
                ->withToken($token)
                ->acceptJson()
                ->get(rtrim(config('services.auth.url'), '/').'/api/me');
        } catch (\Throwable) {
            return redirect()->route('login')->withErrors([
                'email' => 'Le service d’authentification est momentanément indisponible.',
            ]);
        }

        if (! $response->successful()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre session a expiré. Veuillez vous reconnecter.',
            ]);
        }

        $request->session()->put('admin_user', $response->json());

        return $next($request);
    }
}
