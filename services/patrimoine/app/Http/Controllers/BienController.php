<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Notification;
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

            $bien = Bien::forceCreate($validated + [
                'id' => $id,
                'code_inventaire' => $code,
            ]);

            Notification::create([
                'bien_id' => $bien->id,
                'type' => 'bien_created',
                'title' => 'Nouveau bien enregistré',
                'message' => 'Un nouveau bien a été ajouté au registre du patrimoine.',
            ]);

            return $bien;
        });

        return response()->json($bien, 201);
    }

    public function show($id)
    {
        return response()->json(Bien::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $bien = Bien::findOrFail($id);
        $previousState = $bien->etat;
        $validated = $request->validate([
            'libelle' => 'sometimes|string|max:180',
            'etat' => 'sometimes|string|max:40',
            'valeur_acquisition' => 'sometimes|numeric|min:0',
        ]);

        $bien = DB::transaction(function () use ($bien, $previousState, $validated) {
            $bien->update($validated);

            if ($previousState !== 'reforme' && $bien->etat === 'reforme') {
                Notification::create([
                    'bien_id' => $bien->id,
                    'type' => 'bien_reformed',
                    'title' => 'Bien passé à l’état réformé',
                    'message' => 'Un bien a été marqué comme réformé dans le registre.',
                ]);
            }

            return $bien->fresh();
        });

        return response()->json($bien);
    }

    public function destroy($id)
    {
        $bien = Bien::find($id);

        // A DELETE request may be retried after a timeout or after the page
        // has not yet refreshed. Treat an already-deleted asset as success.
        if (!$bien) {
            return response()->noContent();
        }

        DB::transaction(function () use ($bien) {
            Notification::create([
                'bien_id' => $bien->id,
                'type' => 'bien_deleted',
                'title' => 'Bien supprimé du registre',
                'message' => 'Un bien a été supprimé du registre du patrimoine.',
            ]);

            $bien->delete();
        });

        return response()->json(null, 204);
    }
}
