<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Participation extends Model
{
    use CrudTrait, SoftDeletes;

    protected $table = 'participations';

    protected $attributes = [
        'state' => 'En attente de validation',
    ];

    protected $fillable = [
        'mission_id',
        'profile_id',
        'state',
    ];

    protected $with = ['mission:id,name,tuteur_id,structure_id', 'mission.structure:id,name'];


    public function mission()
    {
        return $this->belongsTo('App\Models\Mission')->without('participations');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile')->without('participations');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
            break;
            case 'referent':
                return $query
                    ->where('department', auth()->user()->profile->referent_department);
            break;
            case 'superviseur':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->whereHas('structure', function (Builder $query) {
                            $query->where('reseau_id', auth()->user()->profile->reseau_id);
                        });
                    });
            break;
            case 'responsable':
                return $query
                    ->whereIn('mission_id', auth()->user()->missions->pluck('id'))
                    ->orWhereIn('mission_id', auth()->user()->profile->missionsAsTuteur->pluck('id'));
            break;
            case 'tuteur':
                return $query
                    ->whereIn('mission_id', auth()->user()->profile->missionsAsTuteur->pluck('id'));
            break;
        }
    }
}
