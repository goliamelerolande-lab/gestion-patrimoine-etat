<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'localisation.sites';
    protected $fillable = ['code', 'nom', 'ville', 'region'];
    
    // Désactiver les timestamps car on ne les a pas dans la table SQL
    public $timestamps = false; 
}