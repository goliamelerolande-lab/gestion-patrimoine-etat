<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bien extends Model
{
    protected $table = 'patrimoine.biens';
    protected $fillable = [
        'code_inventaire', 'libelle', 'category_id', 
        'date_acquisition', 'valeur_acquisition', 
        'duree_amortissement_mois', 'etat'
    ];
}