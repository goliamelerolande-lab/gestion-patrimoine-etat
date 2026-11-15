<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bien;

class BienController extends Controller
{
    public function index()
    {
        // Récupérer tous les biens
        return response()->json(Bien::all());
    }

    public function store(Request $request)
    {
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
        
        return response()->json($bien, 201);
    }

    public function show($id)
    {
        // Voir un seul bien
        $bien = Bien::findOrFail($id);
        return response()->json($bien);
    }

    public function update(Request $request, $id)
    {
        // Modifier un bien existant
        $bien = Bien::findOrFail($id);
        
        $validated = $request->validate([
            'libelle' => 'sometimes|string',
            'etat' => 'sometimes|string',
            'valeur_acquisition' => 'sometimes|numeric'
        ]);

        $bien->update($validated);
        return response()->json($bien);
    }

    public function destroy($id)
    {
        // Supprimer un bien
        $bien = Bien::findOrFail($id);
        $bien->delete();
        
        return response()->json(null, 204); // 204 = Succès, aucune donnée retournée
    }
}