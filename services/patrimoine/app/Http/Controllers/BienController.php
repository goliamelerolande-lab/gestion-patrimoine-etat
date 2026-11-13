<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BienController extends Controller
{
    public function index()
    {
        return response()->json(Bien::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'libelle' => 'required|string|max:180',
            'category_id' => 'required|integer',
            'date_acquisition' => 'required|date',
            'valeur_acquisition' => 'required|numeric|min:0',
            'etat' => 'nullable|string|max:40',
        ]);

        $bien = DB::transaction(function () use ($validated) {
            do {
                $id = (int) DB::selectOne(
                    "SELECT nextval(pg_get_serial_sequence('patrimoine.biens', 'id')) AS id"
                )->id;
                $code = sprintf('GPE-%06d', $id);
            } while (Bien::query()->where('code_inventaire', $code)->exists());

            return Bien::forceCreate($validated + [
                'id' => $id,
                'code_inventaire' => $code,
            ]);
        });

        return response()->json($bien, 201);
    }

    public function show($id)
    {
        $bien = Bien::findOrFail($id);

        return response()->json($bien);
    }

    public function update(Request $request, $id)
    {
        $bien = Bien::findOrFail($id);
        $validated = $request->validate([
            'libelle' => 'sometimes|string|max:180',
            'etat' => 'sometimes|string|max:40',
            'valeur_acquisition' => 'sometimes|numeric|min:0',
        ]);

        $bien->update($validated);

        return response()->json($bien);
    }

    public function destroy($id)
    {
        $bien = Bien::findOrFail($id);
        $bien->delete();

        return response()->json(null, 204);
    }
}
