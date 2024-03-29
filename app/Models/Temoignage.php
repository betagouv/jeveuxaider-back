<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Temoignage extends Model
{
    protected $guarded = [
        'id',
    ];

    public function participation()
    {
        return $this->belongsTo('App\Models\Participation');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
            case 'referent':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->where('department', Auth::guard('api')->user()->departmentsAsReferent->first()->number);
                        });
                    });
            case 'referent_regional':
                return $query
                    ->whereHas('participation', function (Builder $query) {
                        $query->whereHas('mission', function (Builder $query) {
                            $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->regionsAsReferent->first()->name]);
                        });
                    });
            case 'tete_de_reseau':
                return $query->ofReseau(Auth::guard('api')->user()->contextable_id);
            case 'responsable':
                return $query->ofStructure(Auth::guard('api')->user()->contextable_id);
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function scopeOfStructure($query, $structure_id)
    {
        return $query->whereHas('participation', function (Builder $query) use ($structure_id) {
            $query->ofStructure($structure_id);
        });
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query->whereHas('participation', function (Builder $query) use ($reseau_id) {
            $query->ofReseau($reseau_id);
        });
    }

    protected function testimony(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value),
        );
    }
}
