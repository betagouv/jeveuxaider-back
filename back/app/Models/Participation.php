<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;

class Participation extends Model
{
    use LogsActivity;

    protected $table = 'participations';

    protected $attributes = [
        'state' => 'En attente de validation',
    ];

    protected $fillable = [
        'mission_id',
        'profile_id',
        'state',
    ];

    const ACTIVE_STATUS = [
        'En attente de validation',
        'Validée',
        'Effectuée'
    ];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission');
    }

    public function profile()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function conversation()
    {
        return $this->morphOne('App\Models\Conversation', 'conversable');
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
                    ->whereHas('mission', function (Builder $query) {
                        $query->where('structure_id', Auth::guard('api')->user()->profile->structures->pluck('id')->first());
                    });
                break;
            case 'responsable_collectivity':
                return $query->collectivity(Auth::guard('api')->user()->profile->collectivity->id);
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

    public function scopeStructure($query, $value)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($value) {
                $query->whereHas('structure', function (Builder $query) use ($value) {
                    $query->where('id', $value);
                });
            });
    }

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('mission', function (Builder $query) use ($domain_id) {
                $query->domaine($domain_id);
            });
    }

    public function scopeCollectivity($query, $collectivity_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($collectivity_id) {
            $query->collectivity($collectivity_id);
        });
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        return $query->whereHas('mission', function (Builder $query) use ($territoire_id) {
            $query->ofTerritoire($territoire_id);
        });
    }

    public function deleteQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->delete($options);
        });
    }
}
