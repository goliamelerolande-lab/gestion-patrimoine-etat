<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
=======
use Illuminate\Http\Request;
use App\Models\Site;
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70

class SiteController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
        return response()->json(Site::query()->orderBy('nom')->get());
=======
        return response()->json(Site::all());
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
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
=======
            'code' => 'required|string',
            'nom' => 'required|string',
            'ville' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        $site = Site::create($validated);
        return response()->json($site, 201);
    }
}
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
