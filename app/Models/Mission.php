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
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Mission extends Model
{
    use SoftDeletes, Searchable, HasTags, LogsActivity, HasSlug;

    protected $table = 'missions';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'publics_beneficiaires' => 'array',
        'publics_volontaires' => 'array',
        'is_priority' => 'boolean',
        'is_snu_mig_compatible' => 'boolean',
        'start_date' => 'datetime:Y-m-d\TH:i',
        'end_date' => 'datetime:Y-m-d\TH:i'
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France'
    ];

    protected $appends = ['full_url', 'full_address'];

    protected $with = ['template'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function shouldBeSearchable()
    {
        if (!$this->structure) {
            return false;
        }
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

    public function makeAllSearchableUsing(Builder $query)
    {
        return $query->with(['structure', 'structure.reseaux', 'template.domaine', 'template.photo', 'illustrations', 'domaine', 'domaineSecondary']);
    }

    public function toSearchableArray()
    {
        $this->load(['structure', 'structure.reseaux:id,name', 'template.domaine', 'template.photo', 'illustrations', 'domaine', 'domaineSecondary']);

        $domaines = [];
        $domaine = $this->template_id ? $this->template->domaine : $this->domaine;
        if ($domaine) {
            $domaines[] = $domaine->name;
        }
        if ($this->domaine_secondary_id) {
            $domaines[] = $this->domaineSecondary->name;
        }

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
                'response_ratio' => $this->structure->response_ratio,
                'reseau' => $this->structure->reseau ? [
                    'id' => $this->structure->reseau->id,
                    'name' => $this->structure->reseau->name,
                ] : null,
                'reseaux' => $this->structure->reseaux ? $this->structure->reseaux->all() : null,
            ] : null,
            'type' => $this->type,
            'template_subtitle' => $this->template ? $this->template->subtitle : null,
            'template_title' => $this->template ? $this->template->title : null,
            // 'domaine_name' => $this->domaine_name, // @TODO: à retirer quand facet ok coté Algolia
            // 'domaine_image' => $this->template ? $this->template->image : $this->domaine->image, // @TODO: à retirer
            'template' => $this->template ? [
                'id' => $this->template->id,
                'title' => $this->template->title,
                'subtitle' => $this->template->subtitle,
                'photo' => $this->template_id ? $this->template->photo : null,
            ] : null,
            'domaine_id' => $domaine ? $domaine->id : null,
            'domaine' => $domaine ? [
                'id' => $domaine->id,
                'name' => $domaine->name,
            ] : null,
            'domaines' => $domaines,
            'provider' => 'reserve_civique',
            'publisher_name' => 'Réserve Civique',
            'post_date' => strtotime($this->created_at),
            'start_date' => $this->start_date ? strtotime($this->start_date) : null,
            'end_date' => $this->end_date ? strtotime($this->end_date) : null,
            'illustrations' => $this->illustrations->all(),
            'template_id' => $this->template_id,
            'score' => $this->score,
            'is_priority' => $this->is_priority,
            'is_snu_mig_compatible' => $this->is_snu_mig_compatible,
            'snu_mig_places' => $this->snu_mig_places,
        ];

        if ($this->latitude && $this->longitude) {
            $mission['_geoloc'] = [
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ];
        }

        return $mission;
    }

    // // @TODO: à retirer quand facet algolia ok
    // public function getDomaineNameAttribute()
    // {
    //     if ($this->template_id) {
    //         return $this->template->domaine ? $this->template->domaine->name : null;
    //     }

    //     return $this->domaine ? $this->domaine->name : null;
    // }

    // public function getDomainesAttribute()
    // {
    //     $domains =  collect([
    //         $this->template ? $this->template->domaine : $this->domaine,
    //         $this->domaine_secondaire
    //     ])->filter();

    //     return $domains;
    // }

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
        return $this->belongsTo('App\Models\Domaine');
    }

    public function domaineSecondary()
    {
        return $this->belongsTo('App\Models\Domaine');
    }

    public function template()
    {
        return $this->belongsTo('App\Models\MissionTemplate');
    }

    public function illustrations()
    {
        return $this->morphToMany(Media::class, 'mediable')->wherePivot('field', 'mission_illustrations');
    }

    public function getFullAddressAttribute()
    {
        return ($this->address == $this->city) ? "{$this->zip} {$this->city}" : "{$this->address} {$this->zip} {$this->city}";
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
        return $this->participations()->count();
    }

    public function getParticipationsValidatedCountAttribute()
    {
        return $this->participations()->state('Validée')->count();
    }

    public function getNameAttribute($value)
    {
        return $this->template_id ? $this->template->title : $value;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($this->attributes['template_id']) ? null : $value;
    }

    // public function getDescriptionAttribute($value)
    // {
    //     return $this->template_id ? $this->template->description : $value;
    // }

    // public function getObjectifAttribute($value)
    // {
    //     return $this->template_id ? $this->template->objectif : $value;
    // }

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

    public function scopeNotOutdated($query)
    {
        return $query
            ->where(function ($query) {
                $query
                    ->where('end_date', '>=', Carbon::now())
                    ->orWhereNull('end_date');
            });
    }

    public function scopeOrganizationState($query, $state)
    {
        return $query->whereHas('structure', function (Builder $query) use ($state) {
            $query->where('state', $state);
        });
    }

    public function scopeCurrent($query)
    {
        return $query
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function scopeAvailable($query)
    {
        return $query->where('missions.state', 'Validée')->whereHas('structure', function (Builder $query) {
            $query->where('structures.state', 'Validée');
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
            ->orWhere('domaine_secondary_id', $domain_id)
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
                return $query->where('structure_id', $user->contextable_id);
                break;
            case 'responsable_territoire':
                $user = Auth::guard('api')->user();
                return $query->ofTerritoire($user->contextable_id);
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
                    ->whereIn(
                        'department',
                        config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]
                    );
                break;
            case 'tete_de_reseau':
                // Missions qui sont dans une structure rattachée à mon réseau
                return $query
                    ->ofReseau(Auth::guard('api')->user()->profile->tete_de_reseau_id);
                break;
            default:
                // Securite
                return $query->whereNull('id');
        }
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query->whereHas('structure', function (Builder $query) use ($reseau_id) {
            $query->ofReseau($reseau_id);
        });
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

    public function duplicate()
    {
        $mission = $this->replicate();
        $mission->state = 'Brouillon';
        $mission->is_priority = false;

        // Si la personne qui clone fait parti des responsables de l'organisation,
        // la mettre en tant que responsable de la mission
        $currentUserProfile = Auth::guard('api')->user()->profile;
        if ($this->structure->members->contains("id", $currentUserProfile->id)) {
            $mission->responsable_id = $currentUserProfile->id;
        }

        $mission->save();

        if ($this->illustrations) {
            $values = $this->illustrations->pluck($this->illustrations, 'id')->map(function ($item) {
                return ['field' => 'mission_illustrations'];
            });
            $mission->illustrations()->sync($values);
        }

        return $mission;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($mission) {
                $mission->load('structure');
                return !empty($mission->city)
                    ? "benevolat-{$mission->structure->name}-{$mission->city}"
                    : "benevolat-{$mission->structure->name}";
            })
            ->saveSlugsTo('slug');
    }

    public function skills()
    {
        return $this->morphToMany(Term::class, 'termable')->wherePivot('field', 'mission_skills');
    }

    public function setCommitmentTotal()
    {
        $this->commitment__total = Utils::calculateCommitmentTotal(
            $this->commitment__duration,
            $this->commitment__time_period
        );
    }

    public function scopeMinimumCommitment($query, $duration, $time_period = null)
    {
        $total = Utils::calculateCommitmentTotal($duration, $time_period);
        return $query->where('commitment__total', '>=', $total);
    }

    public function getPermissionsAttribute()
    {
        return [
            'canFindBenevoles' => $this->state == 'Validée' && $this->structure &&
                $this->structure->state == 'Validée' && $this->has_places_left ? true : false,
        ];
    }

    public function temoignages()
    {
        return $this->hasManyThrough(Temoignage::class, Participation::class);
    }

    public function notificationsTemoignage()
    {
        return $this->hasManyThrough(NotificationTemoignage::class, Participation::class);
    }

    public function getTestimoniesStats()
    {
        $temoignages = $this->temoignages;
        $notificationsTemoignage = $this->notificationsTemoignage;

        return [
            'testimonies' => [
                'count' => $temoignages->count(),
                'average_grade' => $temoignages->avg('grade'),
            ],
            'notifications' => [
                'count' => $notificationsTemoignage->count(),
                'total' => $this->participations_validated_count,
            ]
        ];
    }

    public function sendNotificationsTemoignages()
    {
        $participations = $this->participations()->state('Validée')->get();
        foreach ($participations as $participation) {
            /** @var \App\Models\Participation $participation */
            $participation->sendNotificationTemoignage();
        }
    }

    protected function objectif(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->template_id ?
                strip_tags($this->template->objectif, '<p><b><strong><ul><ol><li><i>') :
                strip_tags($value, '<p><b><strong><ul><ol><li><i>'),
        );
    }

    protected function information(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value, '<p><b><strong><ul><ol><li><i>'),
        );
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->template_id ?
                strip_tags($this->template->description, '<p><b><strong><ul><ol><li><i>') :
                strip_tags($value, '<p><b><strong><ul><ol><li><i>'),
        );
    }
}
