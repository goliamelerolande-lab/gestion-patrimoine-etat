<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserManagementController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return $this->forward($request, 'GET', '/api/users');
    }

    public function store(Request $request): JsonResponse
    {
        return $this->forward($request, 'POST', '/api/users');
    }

    public function update(Request $request, int $id): JsonResponse
    {
        return $this->forward($request, 'PUT', "/api/users/{$id}");
    }

    public function setStatus(Request $request, int $id): JsonResponse
    {
        return $this->forward($request, 'PATCH', "/api/users/{$id}/status");
    }

    private function forward(Request $request, string $method, string $path): JsonResponse
    {
        try {
            $pendingRequest = Http::connectTimeout(1)
                ->timeout(4)
                ->withToken((string) $request->session()->get('admin_token'))
                ->acceptJson();

            $response = $method === 'GET'
                ? $pendingRequest->get(rtrim(config('services.auth.url'), '/').$path)
                : $pendingRequest->send($method, rtrim(config('services.auth.url'), '/').$path, [
                    'json' => $request->except(['_token', '_method']),
                ]);
        } catch (\Throwable) {
            return response()->json(['message' => 'Le service utilisateurs est momentanément indisponible.'], 503);
        }

        return response()->json($response->json(), $response->status());
    }
}
