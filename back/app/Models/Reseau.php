<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reseau extends Model
{
    protected $table = 'reseaux';

    protected $fillable = [
        'name',
    ];
 
    public function profiles()
    {
        return $this->hasMany(Profile::class, 'tete_de_reseau_id');
    }

    public function structures()
    {
        return $this->belongsToMany(Structure::class);
    }
}
