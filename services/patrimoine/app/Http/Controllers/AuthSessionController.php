<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class AuthSessionController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (session()->has('admin_token')) {
            return redirect()->route('users.index');
        }

        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email|max:150',
            'password' => 'required|string|max:200',
        ]);

        try {
            $response = Http::connectTimeout(1)
                ->timeout(3)
                ->acceptJson()
                ->post(rtrim(config('services.auth.url'), '/').'/api/login', $validated);
        } catch (\Throwable) {
            return back()->withErrors(['email' => 'Le service d’authentification est momentanément indisponible.'])
                ->onlyInput('email');
        }

        $payload = $response->json();

        if (! $response->successful() || ! is_array($payload) || empty($payload['token']) || empty($payload['user'])) {
            return back()->withErrors(['email' => 'Adresse email ou mot de passe incorrect.'])->onlyInput('email');
        }

        $request->session()->regenerate();
        $request->session()->put([
            'admin_token' => $payload['token'],
            'admin_user' => $payload['user'],
            'admin_expires_at' => $payload['expires_at'] ?? null,
        ]);

        return redirect()->route('users.index');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $token = $request->session()->get('admin_token');

        if ($token) {
            try {
                Http::connectTimeout(1)
                    ->timeout(2)
                    ->withToken($token)
                    ->post(rtrim(config('services.auth.url'), '/').'/api/logout');
            } catch (\Throwable) {
                // La session locale est quand même invalidée si auth-service ne répond pas.
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
