<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categorie extends Model
{
    protected $table = 'patrimoine.categories';
    protected $fillable = ['code', 'libelle'];
    
    // On désactive les timestamps pour cette table
    public $timestamps = false; 
}