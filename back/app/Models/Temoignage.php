<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Temoignage extends Model
{
    protected $fillable = [
        'participation_id',
        'grade',
        'testimony',
    ];

    public function participation()
    {
        return $this->belongsTo('App\Models\Participation');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
                break;
            case 'referent':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->where('department', Auth::guard('api')->user()->profile->referent_department);
                        });
                    });
                break;
            case 'referent_regional':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
                        });
                    });
                break;
            case 'tete_de_reseau':
                return $query
                    ->whereHas('participation.mission.structure.reseaux', function (Builder $query) {
                        $query->where('reseaux.id', Auth::guard('api')->user()->profile->reseau_id);
                    });
                break;
            case 'responsable':
                $user = Auth::guard('api')->user();
                return $query
                    ->whereHas('participation', function (Builder $query) use ($user) {
                        $query->whereHas('mission', function (Builder $query) use ($user) {
                            if ($user->context_role == 'responsable' && $user->contextable_type == 'structure' && !empty($user->contextable_id)) {
                                $query->where('structure_id', $user->contextable_id);
                            } else {
                                $query->where('structure_id', $user->profile->structures->pluck('id')->first());
                            }
                        });
                    });
                break;
        }
    }
}
