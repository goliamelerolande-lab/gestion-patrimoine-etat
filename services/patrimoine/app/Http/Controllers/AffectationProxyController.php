<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AffectationProxyController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'bien_id' => 'required|integer',
            'site_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);
        $validated['user_id'] = (int) $request->session()->get('admin_user.id');

        try {
            $response = Http::connectTimeout(3)
                ->timeout(45)
                ->withToken((string) $request->session()->get('admin_token'))
                ->acceptJson()
                ->post(rtrim(config('services.localisation.url'), '/').'/api/affectations', $validated);
        } catch (\Throwable) {
            return response()->json(['message' => 'Le service Localisation est momentanement indisponible.'], 503);
        }

        return response()->json($response->json(), $response->status());
    }
}
