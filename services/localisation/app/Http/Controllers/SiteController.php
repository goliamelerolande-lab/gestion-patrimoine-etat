<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SiteController extends Controller
{
    public function index()
    {
        return response()->json(Site::query()->orderBy('nom')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:120',
            'ville' => 'nullable|string|max:80',
            'region' => 'nullable|string|max:80',
        ]);

        $site = DB::transaction(function () use ($validated): Site {
            do {
                $id = (int) DB::selectOne(
                    "SELECT nextval(pg_get_serial_sequence('localisation.sites', 'id')) AS id"
                )->id;
                $code = sprintf('SITE-%02d', $id);
            } while (Site::query()->where('code', $code)->exists());

            return Site::forceCreate($validated + [
                'id' => $id,
                'code' => $code,
            ]);
        });

        return response()->json($site, 201);
    }

    public function show($id)
    {
        return response()->json(Site::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $site = Site::findOrFail($id);
        $validated = $request->validate([
            'nom' => 'sometimes|required|string|max:120',
            'ville' => 'nullable|string|max:80',
            'region' => 'nullable|string|max:80',
        ]);

        $site->update($validated);

        return response()->json($site->fresh());
    }

    public function destroy($id)
    {
        $site = Site::findOrFail($id);
        $site->delete();

        return response()->json(null, 204);
    }
}
