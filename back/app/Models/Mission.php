<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Spatie\Tags\HasTags;
use Spatie\Activitylog\Traits\LogsActivity;
use Carbon\Carbon;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Helpers\Utils;

class Mission extends Model
{
    use SoftDeletes, Searchable, HasTags, LogsActivity, HasSlug;

    protected $table = 'missions';

    protected $fillable = [
        'name',
        'information',
        'objectif',
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
        'state',
        'participations_max',
        'dates_infos',
        'responsable_id',
        'periodicite',
        'publics_beneficiaires',
        'publics_volontaires',
        'type',
        'domaine_id',
        'template_id',
        'thumbnail',
        'slug',
        'commitment__duration',
        'commitment__time_period',
    ];

    protected $casts = [
        'publics_beneficiaires' => 'array',
        'publics_volontaires' => 'array'
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France'
    ];

    protected $appends = ['full_address', 'has_places_left', 'participations_count', 'participations_total', 'domaine_name', 'permissions'];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function shouldBeSearchable()
    {
        // Attention  bien mettre à jour la query côté API Engagement aussi ( Api\EngagementController@feed )
        return $this->structure->state == 'Validée' && $this->state == 'Validée' ? true : false;
    }

    public function searchableAs()
    {
        return config('scout.prefix') . '_covid_missions';
    }

    public function getFullUrlAttribute()
    {
        return "/missions-benevolat/$this->id/$this->slug";
    }

    public function toSearchableArray()
    {
        $mission = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'city' => $this->city,
            'state' => $this->state,
            'department' => $this->department,
            'department_name' => $this->department . ' - ' . config('taxonomies.departments.terms')[$this->department],
            'zip' => $this->zip,
            'periodicite' => $this->periodicite,
            'has_places_left' => $this->has_places_left,
            'places_left' => $this->places_left,
            'participations_max' => $this->participations_max,
            'structure' => $this->structure ? [
                'id' => $this->structure->id,
                'name' => $this->structure->name,
                'response_time' => $this->structure->response_time,
                'response_time_score' => $this->structure->response_time_score,
                'response_ratio' => $this->structure->response_ratio
            ] : null,
            'type' => $this->type,
            'template_title' => $this->template ? $this->template->title : null,
            'domaine_name' => $this->domaine_name,
            'domaine_image' => $this->template ? $this->template->image : $this->domaine->image,
            'domaines' => $this->domaines->map(function ($domaine) {
                return $domaine->name;
            }),
            'provider' => 'reserve_civique',
            'publisher_name' => 'Réserve Civique',
            'post_date' => strtotime($this->created_at),
            'start_date' => $this->start_date ? strtotime($this->start_date) : null,
            'end_date' => $this->end_date ? strtotime($this->end_date) : null,
            'format' => $this->format,
            'thumbnail' => $this->thumbnail,
            'domaine_id' => $this->domaine_id,
            'template_id' => $this->template_id,
            'score' => $this->score
        ];

        if ($this->latitude && $this->longitude) {
            $mission['_geoloc'] = [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }

        return $mission;
    }

    public function getDomaineNameAttribute()
    {
        return $this->template ? $this->template->domaine->name : $this->domaine->name;
    }

    public function getDomainesAttribute()
    {

        $domains =  collect([
            $this->template ? $this->template->domaine : $this->domaine,
            $this->domaine_secondaire
        ])->filter();

        return $domains;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function responsable()
    {
        return $this->belongsTo('App\Models\Profile');
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation', 'mission_id');
    }

    public function domaine()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\MissionTemplate');
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

    public function getParticipationsTotalAttribute()
    {
        return $this->participations->count();
    }

    public function getNameAttribute($value)
    {
        return $this->template_id ? $this->template->subtitle : $value;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($this->attributes['template_id']) ? null : $value;
    }

    public function getDescriptionAttribute($value)
    {
        return $this->template_id ? $this->template->description : $value;
    }

    public function getObjectifAttribute($value)
    {
        return $this->template_id ? $this->template->objectif : $value;
    }

    public function getScoreAttribute()
    {
        // Score = ( Taux de reponse score + Response time score ) / 2
        $structure_response_ratio = $this->structure->response_ratio ?? 50;
        return round(($this->structure->response_time_score + $structure_response_ratio) / 2);
    }


    public function scopeHasPlacesLeft($query)
    {
        return $query->where('places_left', '>', 0);
    }

    public function scopeComplete($query)
    {
        return $query->where('places_left', '<=', 0);
    }

    public function scopeIncoming($query)
    {
        return $query
            ->where('start_date', '>=', Carbon::now());
    }

    public function scopeOutdated($query)
    {
        return $query
            ->where('end_date', '<', Carbon::now());
    }

    public function scopeCurrent($query)
    {
        return $query
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function scopeAvailable($query)
    {
        return $query->where('state', 'Validée')->whereHas('structure', function (Builder $query) {
            $query->where('state', 'Validée');
        });
    }

    public function scopeDepartment($query, $value)
    {
        return $query->where('department', $value);
    }

    public function scopeRegion($query, $value)
    {
        return $query
            ->whereIn('department', config('taxonomies.regions.departments')[$value]);
    }

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->where('domaine_id', $domain_id)
            ->orWhereHas('tags', function (Builder $query) use ($domain_id) {
                $query->where('id', $domain_id);
            })
            ->orWhereHas('template', function (Builder $query) use ($domain_id) {
                $query->where('domaine_id', $domain_id);
            });
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        $territoire = Territoire::find($territoire_id);

        if ($territoire->type == 'department') {
            return $query
                ->where('department', $territoire->department);
        }

        if ($territoire->type == 'city') {
            return $query
                ->whereIn('zip', $territoire->zips);
        }
    }

    public function scopeName($query, $value)
    {
        return $query->where('name', $value);
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
                $user = Auth::guard('api')->user();
                if ($user->context_role == 'responsable' && $user->contextable_type == 'structure' && !empty($user->contextable_id)) {
                    return $query
                        ->where('structure_id', $user->contextable_id);
                } else {
                    return $query
                        ->where('structure_id', $user->profile->structures->pluck('id')->first());
                }
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
        $mission->state = 'Brouillon';
        $mission->save();

        return $mission;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($mission) {
                return !empty($mission->city)
                    ? "benevolat-{$mission->structure->name}-{$mission->city}"
                    : "benevolat-{$mission->structure->name}";
            })
            ->saveSlugsTo('slug');
    }

    public function getSkillsAttribute()
    {
        return $this->tagsWithType('competence')->values();
    }

    public function getDomaineSecondaireAttribute()
    {
        return $this->tagsWithType('domaine')->first();
    }

    public function setCommitmentTotal()
    {
        $this->commitment__total = Utils::calculateCommitmentTotal(
            $this->commitment__duration,
            $this->commitment__time_period
        );
    }

    public function getPermissionsAttribute()
    {
        return [
            'canFindBenevoles' => $this->state == 'Validée' && $this->structure->state == 'Validée' && $this->has_places_left ? true : false,
        ];
    }
}
