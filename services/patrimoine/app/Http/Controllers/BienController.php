<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Bien;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
=======
use Illuminate\Http\Request;
use App\Models\Bien;
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70

class BienController extends Controller
{
    public function index()
    {
<<<<<<< HEAD
=======
        // Récupérer tous les biens
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
        return response()->json(Bien::all());
    }

    public function store(Request $request)
    {
<<<<<<< HEAD
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

=======
        // Créer un nouveau bien
        $validated = $request->validate([
            'code_inventaire' => 'required|string',
            'libelle' => 'required|string',
            'category_id' => 'required|integer',
            'date_acquisition' => 'required|date',
            'valeur_acquisition' => 'required|numeric',
            'etat' => 'nullable|string'
        ]);

        $bien = Bien::create($validated);
        
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
        return response()->json($bien, 201);
    }

    public function show($id)
    {
<<<<<<< HEAD
        return response()->json(Bien::findOrFail($id));
=======
        // Voir un seul bien
        $bien = Bien::findOrFail($id);
        return response()->json($bien);
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
    }

    public function update(Request $request, $id)
    {
<<<<<<< HEAD
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

=======
        // Modifier un bien existant
        $bien = Bien::findOrFail($id);
        
        $validated = $request->validate([
            'libelle' => 'sometimes|string',
            'etat' => 'sometimes|string',
            'valeur_acquisition' => 'sometimes|numeric'
        ]);

        $bien->update($validated);
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
        return response()->json($bien);
    }

    public function destroy($id)
    {
<<<<<<< HEAD
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
=======
        // Supprimer un bien
        $bien = Bien::findOrFail($id);
        $bien->delete();
        
        return response()->json(null, 204); // 204 = Succès, aucune donnée retournée
    }
}
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
