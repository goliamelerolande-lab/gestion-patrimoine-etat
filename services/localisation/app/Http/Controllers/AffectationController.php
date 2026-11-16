<?php

namespace App\Http\Controllers;

<<<<<<< HEAD
use App\Models\Affectation;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
=======
use Illuminate\Http\Request;
use App\Models\Affectation;
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70

class AffectationController extends Controller
{
    public function index()
    {
        return response()->json(Affectation::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'bien_id' => 'required|integer|exists:patrimoine.biens,id',
            'site_id' => 'required|integer|exists:localisation.sites,id',
            'user_id' => 'required|integer|exists:auth.users,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        $affectation = DB::transaction(function () use ($validated): Affectation {
            $dateDebut = Carbon::parse($validated['date_debut'])->toDateString();
            $activeAssignments = Affectation::query()
                ->where('bien_id', $validated['bien_id'])
                ->whereNull('date_fin')
                ->lockForUpdate()
                ->get();

            foreach ($activeAssignments as $activeAssignment) {
                if ((string) $activeAssignment->site_id === (string) $validated['site_id']) {
                    throw ValidationException::withMessages([
                        'bien_id' => 'Ce bien est deja affecte a ce site.',
                    ]);
                }

                $datePrecedente = Carbon::parse($activeAssignment->date_debut);
                if (Carbon::parse($dateDebut)->lessThanOrEqualTo($datePrecedente)) {
                    throw ValidationException::withMessages([
                        'date_debut' => 'La nouvelle affectation doit commencer apres la precedente.',
                    ]);
                }

                $activeAssignment->update([
                    'date_fin' => Carbon::parse($dateDebut)->subDay()->toDateString(),
                ]);
            }

            return Affectation::create($validated);
        });

=======
            'bien_id' => 'required|integer',
            'site_id' => 'required|integer',
            'user_id' => 'required|integer',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
        ]);

        $affectation = Affectation::create($validated);
>>>>>>> 32310393ff8aa66dd7db79c6f3ddf821bba07b70
        return response()->json($affectation, 201);
    }
}