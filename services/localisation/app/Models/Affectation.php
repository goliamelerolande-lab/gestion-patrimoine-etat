<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $table = 'localisation.affectations';
    protected $fillable = ['bien_id', 'site_id', 'user_id', 'date_debut', 'date_fin'];
    
    public $timestamps = false;
}