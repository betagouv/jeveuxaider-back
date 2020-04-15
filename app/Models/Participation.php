<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Participation extends Model
{
    use SoftDeletes;

    protected $table = 'participations';

    protected $attributes = [
        'state' => 'En attente de validation',
    ];

    protected $fillable = [
        'mission_id',
        'profile_id',
        'state',
    ];

    protected $with = ['mission', 'profile'];

    const ACTIVE_STATUS = [
        'En attente de validation',
        'Mission validée',
        'Mission en cours',
        'Mission effectuée'
    ];

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
            case 'analyste':
                return $query;
            break;
            case 'referent':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->where('department', Auth::guard('api')->user()->profile->referent_department);
                    });
            break;
            case 'referent_regional':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
                    });
            break;
            case 'superviseur':
                return $query
                    ->whereHas('mission', function (Builder $query) {
                        $query->whereHas('structure', function (Builder $query) {
                            $query->where('reseau_id', Auth::guard('api')->user()->profile->reseau_id);
                        });
                    });
            break;
            case 'responsable':
                return $query
                    ->whereIn('mission_id', Auth::guard('api')->user()->missions->pluck('id'))
                    ->orWhereIn('mission_id', Auth::guard('api')->user()->profile->missions->pluck('id'));
            break;
            case 'tuteur':
                return $query
                    ->whereIn('mission_id', Auth::guard('api')->user()->profile->missions->pluck('id'));
            break;
        }
    }

    public function scopeDepartment($query, $value)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query->where('department', $value);
            });
    }
}
