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

    public function invitationsResponsables()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable')->where('role', 'responsable_reseau');
    }

    public function invitationsAntennes()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable')->where('role', 'responsable_antenne');
    }

    public function deleteResponsable(Profile $profile)
    {
        $profile->tete_de_reseau_id = null;
        $profile->save();

        return $this->load('responsables');
    }

    public function createStructure(string $name , User $user, array $attributes = [])
    {

        $attributes = array_merge([
            'name' => $name,
            'user_id' => $user->id,
            'statut_juridique' => 'Association',
        ], $attributes);

        $structure = Structure::create($attributes);

        $this->structures()->attach($structure->id);

        // UPDATE LOG
        Activity::where('subject_type', 'App\Models\Structure')
            ->where('subject_id', $structure->id)
            ->where('description', 'created')
            ->update(
                [
                    'causer_id' => $user->id,
                    'causer_type' => 'App\Models\User',
                    'data' => [
                        "subject_title" => $structure->name,
                        "full_name" => $user->profile->full_name,
                        "causer_id" => $user->profile->id,
                        "context_role" => 'responsable'
                    ]
                ]
            );

        return $structure;

    }

}
