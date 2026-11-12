<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Affectation;

class AffectationController extends Controller
{
    public function index()
    {
        return response()->json(Affectation::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bien_id' => 'required|integer',
            'site_id' => 'required|integer',
            'user_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
        ]);

        $affectation = Affectation::create($validated);
        return response()->json($affectation, 201);
    }
}