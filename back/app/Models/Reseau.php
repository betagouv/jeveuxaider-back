<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reseau extends Model
{
    protected $table = 'reseaux';

    protected $fillable = [
        'name',
        'created_at',
    ];

    public function responsables()
    {
        return $this->hasMany(Profile::class, 'tete_de_reseau_id');
    }

    public function structures()
    {
        return $this->belongsToMany(Structure::class);
    }

    public function missionTemplates()
    {
        return $this->hasMany(MissionTemplate::class);
    }

    public function antennesInvitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function responsablesInvitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }
}
