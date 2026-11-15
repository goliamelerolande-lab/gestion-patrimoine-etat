<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Site;

class SiteController extends Controller
{
    public function index()
    {
        return response()->json(Site::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string',
            'nom' => 'required|string',
            'ville' => 'nullable|string',
            'region' => 'nullable|string',
        ]);

        $site = Site::create($validated);
        return response()->json($site, 201);
    }
}