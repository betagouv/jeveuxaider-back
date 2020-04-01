<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Helpers\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Scout\Searchable;

class Mission extends Model
{
    use CrudTrait, SoftDeletes, Searchable;

    protected $table = 'missions';

    protected $fillable = [
        'name',
        'description',
        'address',
        'zip',
        'city',
        'department',
        'country',
        'latitude',
        'longitude',
        'user_id',
        'structure_id',
        'start_date',
        'end_date',
        'format',
        'domaines',
        'state',
        'participations_max',
        'dates_infos',
        'periodes',
        'frequence',
        'planning',
        'actions',
        'justifications',
        'contraintes',
        'handicap',
        'tuteur_id',
        'periodicite',
        'publics_beneficiaires',
        'publics_volontaires',
        'type'
    ];

    protected $casts = [
        'domaines' => 'array',
        'periodes' => 'array',
        'planning' => 'array',
        'publics_beneficiaires' => 'array',
        'publics_volontaires' => 'array'
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France'
    ];

    protected $appends = ['full_address', 'has_places_left', 'participations_count'];

    protected $with = [
        'structure:id,name,city,address,zip',
        'structure.members:id,first_name,last_name,mobile,email',
        'tuteur:id,email,mobile,phone,first_name,last_name'
    ];

    public function shouldBeSearchable()
    {
        return $this->state == 'Validée' && $this->has_places_left ? true : false;
    }

    public function searchableAs()
    {
        return config('scout.prefix').'_covid_missions';
    }

    public function toSearchableArray()
    {
        $mission = [
            'id' => $this->id,
            'name' => $this->name,
            'city' => $this->city,
            'department' => $this->department,
            'department_name' => $this->department . ' - ' . config('taxonomies.departments.terms')[$this->department],
            'zip' => $this->zip,
            'domaine_action' => $this->name,
            'periodicite' => $this->periodicite,
            'has_places_left' => $this->has_places_left,
            'places_left' => $this->places_left,
            'participations_max' => $this->participations_max,
            'structure' => $this->structure ? [
                'id' => $this->structure->id,
                'name' => $this->structure->name,
            ] : null,
            'type' => $this->type
        ];

        if ($this->latitude && $this->longitude) {
            $mission["_geoloc"] = [
                "lat" => $this->latitude,
                "lng" => $this->longitude
            ];
        }

        return $mission;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Utils::ucfirst($value);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function tuteur()
    {
        return $this->belongsTo('App\Models\Profile')->without('structures');
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation', 'mission_id')->without('mission');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address} {$this->zip} {$this->city}";
    }

    public function getHasPlacesLeftAttribute()
    {
        return $this->places_left > 0 ? true : false;
    }

    public function getParticipationsCountAttribute()
    {
        return $this->participations_max - $this->places_left;
    }

    public function scopeHasPlacesLeft($query)
    {
        return $query->where('places_left', '>', 0);
    }

    public function scopeComplete($query)
    {
        return $query->where('places_left', '<=', 0);
    }

    public function scopeAvailable($query)
    {
        return $query->where('state', 'Validée');
    }

    public function scopeDepartment($query, $value)
    {
        return $query->where('department', $value);
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
            break;
            case 'responsable':
                // Missions des structures dont je suis responsable
                return $query
                    ->whereIn('structure_id', Auth::guard('api')->user()->profile->structures->pluck('id'));
            break;
            case 'tuteur':
                // Missions dont je suis le tuteur
                return $query
                    ->where('tuteur_id', Auth::guard('api')->user()->profile->id);
            break;
            case 'referent':
                // Missions qui sont dans mon département
                return $query
                    ->whereNotNull('department')
                    ->where('department', Auth::guard('api')->user()->profile->referent_department);
            break;
            case 'referent_regional':
                // Missions qui sont dans ma région
                return $query
                    ->whereNotNull('department')
                    ->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
            break;
            case 'superviseur':
                // Missions qui sont dans une structure rattachée à mon réseau
                return $query
                    ->whereHas('structure', function (Builder $query) {
                        $query->where('reseau_id', Auth::guard('api')->user()->profile->reseau->id);
                    });
            break;
        }
    }

    public function scopeDistance($query, $latitude, $longitude)
    {
        $latName = 'latitude';
        $lonName = 'longitude';
        $query->select($this->getTable() . '.*');
        $sql = "((ACOS(SIN(? * PI() / 180) * SIN(" . $latName . " * PI() / 180) + COS(? * PI() / 180) * COS(" .
        $latName . " * PI() / 180) * COS((? - " . $lonName . ") * PI() / 180)) * 180 / PI()) * 60 * ?) as distance";

        $query->selectRaw($sql, [$latitude, $latitude, $longitude, 1.1515 * 1.609344]);

        return $query;
    }

    public function clone()
    {
        $mission = $this->replicate();
        // $mission->state = 'Brouillon';
        $mission->save();

        return $mission;
    }
}
