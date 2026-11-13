<?php

namespace App\Http\Controllers;

use App\Models\Site;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SiteController extends Controller
{
    public function index()
    {
        return response()->json(Site::query()->orderBy('nom')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:localisation.sites,code',
            'nom' => 'required|string|max:120',
            'ville' => 'nullable|string|max:80',
            'region' => 'nullable|string|max:80',
        ]);

        return response()->json(Site::create($validated), 201);
    }

    public function show($id)
    {
        return response()->json(Site::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $site = Site::findOrFail($id);
        $validated = $request->validate([
            'code' => ['sometimes', 'required', 'string', 'max:20', Rule::unique('localisation.sites', 'code')->ignore($site->id)],
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
