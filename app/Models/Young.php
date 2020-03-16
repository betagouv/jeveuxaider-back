<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Young extends Model
{
    use CrudTrait, SoftDeletes;

    protected $table = 'youngs';

    protected $attributes = [
        'state' => 'En attente de mission',
    ];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'department',
        'address',
        'zip',
        'city',
        'latitude',
        'longitude',
        'regular_city',
        'regular_latitude',
        'regular_longitude',
        'engaged',
        'engaged_structure',
        'interet_defense',
        'interet_defense_type',
        'interet_defense_domaine',
        'interet_defense_motivation',
        'interet_securite',
        'interet_securite_domaine',
        'interet_solidarite',
        'interet_sante',
        'interet_education',
        'interet_culture',
        'interet_sport',
        'interet_environnement',
        'interet_citoyennete',
        'mission_format',
        'mission_autonome_projet',
        'mission_autonome_structure',
        'contraintes',
        'situation',
        'genre',
        'notes',
        'mission_id',
        'state',
    ];

    protected $appends = ['full_name', 'full_address'];

    protected $with = ['mission:id,name,tuteur_id,structure_id', 'mission.structure:id,name'];

    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->zip} {$this->city}";
    }

    public function setFirstNameAttribute($value)
    {
        $this->attributes['first_name'] = Utils::ucfirst($value);
    }

    public function setLastNameAttribute($value)
    {
        $this->attributes['last_name'] = Utils::ucfirst($value);
    }

    public function getPreferencesAttribute()
    {
        $domaines = [
            'interet_defense' => "Défense et mémoire",
            'interet_securite' => "Sécurité",
            'interet_solidarite' => "Solidarité",
            'interet_sante' => "Santé",
            'interet_education' => "Éducation",
            'interet_culture' => "Culture",
            'interet_sport' => "Sport",
            'interet_environnement' => "Environnement et développement durable",
            'interet_citoyennete' => "Citoyenneté"
        ];
        $domaines_favoris = [];
        foreach ($domaines as $key => $domaine) {
            if ($this->{$key} == 'Très intéressé') {
                $domaines_favoris[] = $domaine;
            }
        }
        return ["format" => $this->mission_format, "domaine" => $domaines_favoris];
    }

    public function mission()
    {
        return $this->belongsTo('App\Models\Mission')->without('youngs');
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

    public function scopeHasNotValidEmail($query)
    {
        return $query->where('email', 'not regexp', '^[^@]+@[^@]+\.[^@]{2,}$');
    }

    public function scopeHasNotValidGeolocalisation($query)
    {
        return $query->whereNull('regular_latitude')->orWhereNull('regular_longitude');
    }
}
