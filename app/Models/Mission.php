<?php

namespace App\Models;

use App\Helpers\Utils;
use App\Traits\Notable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

// use Spatie\Tags\HasTags;

class Mission extends Model
{
    use SoftDeletes;
    use Searchable;
    use HasFactory;
    // use HasTags;
    use LogsActivity;
    use HasSlug;
    use Notable;

    protected $table = 'missions';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'publics_beneficiaires' => 'array',
        'publics_volontaires' => 'array',
        'is_priority' => 'boolean',
        'is_motivation_required' => 'boolean',
        'is_snu_mig_compatible' => 'boolean',
        // 'start_date' => 'datetime:Y-m-d\TH:i',
        // 'end_date' => 'datetime:Y-m-d\TH:i',
        'start_date' => 'datetime:Y-m-d',
        'end_date' => 'datetime:Y-m-d',
        // 'latitude' => 'float',
        // 'longitude' => 'float',
        // 'is_autonomy' => 'boolean',
        'is_qpv' => 'boolean',
        // 'autonomy_zips' => 'json',
        'addresses' => 'json',
        'dates' => 'json',
        'prerequisites' => 'array',
        'is_registration_open' => 'boolean',
        'is_online' => 'boolean',
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France',
    ];

    protected $appends = ['full_url', 'full_address'];

    protected $with = ['template'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logExcept(['places_left', 'updated_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function shouldBeSearchable()
    {
        if (!$this->structure) {
            return false;
        }

        if (!$this->department) {
            return false;
        }

        // Attention  bien mettre à jour la query côté API Engagement aussi ( Api\EngagementController@feed )
        return $this->structure->state == 'Validée' && $this->state == 'Validée' && $this->is_online ? true : false;
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
        return $query->with(['structure','structure.logo', 'structure.reseaux:id,name', 'activity', 'template.activity', 'template.domaine', 'template.domaineSecondary', 'template.photo', 'illustrations', 'domaine', 'domaineSecondary', 'tags', 'structure.score', 'activitySecondary', 'template.activitySecondary']);
    }

    public function toSearchableArray()
    {
        $this->load(['structure', 'structure.logo', 'structure.reseaux:id,name', 'activity', 'template.activity', 'template.domaine', 'template.domaineSecondary', 'template.photo', 'illustrations', 'domaine', 'domaineSecondary', 'tags', 'structure.score', 'activitySecondary', 'template.activitySecondary']);


        if ($this->template) {
            $domainesCollection = collect([$this->template->domaine, $this->template->domaineSecondary]);
            $activities = collect([$this->template->activity, $this->template->activitySecondary]);
        } else {
            $domainesCollection = collect([$this->domaine, $this->domaineSecondary]);
            $activities = collect([$this->activity, $this->activitySecondary]);
        }
        $domaines = $domainesCollection->filter()->map(fn ($domaine) => ['id' => $domaine->id, 'name' => $domaine->name]);
        $domaineNames = $domainesCollection->filter()->map(fn ($domaine) => $domaine->name);
        $activities = $activities->filter()->map(fn ($activity) => ['id' => $activity->id, 'name' => $activity->name]);
        $publicsBeneficiaires = config('taxonomies.mission_publics_beneficiaires.terms');

        if ($this->end_date) {
            $trueEndDate = (new Carbon($this->end_date))->endOfDay();
        }

        $mission = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            // 'city' => $this->city,
            'state' => $this->state,
            'department' => $this->department,
            'department_name' => $this->department . ' - ' . config('taxonomies.departments.terms')[$this->department],
            // 'zip' => $this->zip,
            'addresses' => $this->addresses,
            'periodicite' => $this->periodicite,
            'has_places_left' => $this->has_places_left,
            'places_left' => $this->places_left,
            'participations_max' => $this->participations_max,
            'structure' => $this->structure ? [
                'id' => $this->structure->id,
                'name' => $this->structure->name,
                'response_time' => $this->structure->score->response_time,
                'score' => $this->structure->score->total_points,
                'response_ratio' => $this->structure->score->processed_participations_rate,
                'logo' => $this->structure->logo,
                'reseau' => $this->structure->reseau ? [
                    'id' => $this->structure->reseau->id,
                    'name' => $this->structure->reseau->name,
                ] : null,
                'reseaux' => $this->structure->reseaux ? $this->structure->reseaux->all() : null,
            ] : null,
            'type' => $this->type,
            'template_subtitle' => $this->template ? $this->template->subtitle : null,
            'template_title' => $this->template ? $this->template->title : null,
            'template' => $this->template ? [
                'id' => $this->template->id,
                'title' => $this->template->title,
                'subtitle' => $this->template->subtitle,
                'photo' => $this->template_id ? $this->template->photo : null,
            ] : null,
            'activity' => $activities->first(),
            'activities' => $activities->values(),
            'domaine_id' => $domaines->first() ? $domaines->first()['id'] : null,
            'domaine_secondary_id' => $domaines->count() > 1 ? $domaines->last()['id'] : null,
            'domaine' => $domaines->first(),
            'domaines' => $domaineNames->values(),
            'provider' => 'reserve_civique',
            'publisher_name' => 'JeVeuxAider.gouv.fr',
            'post_date' => strtotime($this->created_at),
            'start_date' => $this->start_date ? strtotime($this->start_date) : null,
            'end_date' => $this->end_date ? strtotime($this->end_date) : null,
            'illustrations' => $this->illustrations->all(),
            'template_id' => $this->template_id,
            'is_priority' => $this->is_priority,
            'is_snu_mig_compatible' => $this->is_snu_mig_compatible,
            'snu_mig_places' => $this->snu_mig_places,
            'commitment__total' => $this->commitment__total,
            'commitment__time_period' => $this->commitment__time_period,
            'commitment__duration' => $this->commitment__duration,
            'commitment' => $this->commitment,
            'publics_beneficiaires' => is_array($this->publics_beneficiaires) ? array_map(
                function ($public) use ($publicsBeneficiaires) {
                    return $publicsBeneficiaires[$public];
                },
                $this->publics_beneficiaires
            ) : null,
            'publics_volontaires' => $this->publics_volontaires,
            // 'is_autonomy' => $this->is_autonomy,
            // 'autonomy_zips' => $this->is_autonomy && count($this->autonomy_zips) > 0 ? $this->autonomy_zips : null,
            'is_outdated' => isset($trueEndDate) && (Carbon::today())->gt($trueEndDate) ? true : false,
            'tags' => $this->tags->where('is_published', true)->pluck('name'),
            'is_registration_open' => $this->is_registration_open,
            'date_type' => $this->date_type,
            'dates' => $this->dates ? collect($this->dates)->map(
                function ($item) {
                    return array_merge($item, ['timestamp' => strtotime($item['date'])]);
                }
            )->all() : null,
            'has_creneaux' => !!$this->dates,
            'has_end_date' => !!$this->end_date,
            'end_date_no_creneaux' => $this->dates ? null : strtotime($this->end_date)
        ];

        $mission['_geoloc'] = [];

        if($this->addresses) {
            foreach ($this->addresses as $item) {
                $mission['_geoloc'][] = [
                    'lat' => (float)$item['latitude'],
                    'lng' => (float)$item['longitude'],
                ];
            }
        }

        return $mission;
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    // public function responsable()
    // {
    //     return $this->belongsTo('App\Models\Profile');
    // }

    public function responsables()
    {
        return $this->belongsToMany('App\Models\Profile', 'missions_responsables', 'mission_id', 'responsable_id');
    }

    public function participations()
    {
        return $this->hasMany('App\Models\Participation', 'mission_id');
    }

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
    }

    public function activitySecondary()
    {
        return $this->belongsTo('App\Models\Activity');
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

    public function usersInWaitingList()
    {
        return $this->belongsToMany('App\Models\User', 'missions_users_waiting_list')->whereNull('missions_users_waiting_list.deleted_at');
    }

    public function usersInFavorite()
    {
        return $this->belongsToMany('App\Models\User', 'missions_users_favorites')->withTimestamps();
    }

    public function illustrations()
    {
        return $this->morphToMany(Media::class, 'mediable')->wherePivot('field', 'mission_illustrations');
    }

    public function getPictureAttribute()
    {
        if ($this->template_id) {
            return $this->template?->photo?->urls;
        }

        return $this->illustrations->first() ? $this->illustrations->first()->urls : null;
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
        if($this->template_id) {
            return $this->template->title;
        }
        return $value;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($this->attributes['template_id']) ? null : $value;
    }

    public function scopeHasPlacesLeft($query)
    {
        return $query->where('places_left', '>', 0);
    }

    public function scopeOfStructure($query, $id)
    {
        return $query->where('structure_id', $id);
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
            ->where(
                function ($query) {
                    $query
                        ->where('end_date', '>=', Carbon::now())
                        ->orWhereNull('end_date');
                }
            );
    }

    public function scopeOrganizationState($query, $state)
    {
        return $query->whereHas(
            'structure',
            function (Builder $query) use ($state) {
                $query->where('state', $state);
            }
        );
    }

    public function scopeCurrent($query)
    {
        return $query
            ->where('start_date', '<=', Carbon::now())
            ->where('end_date', '>=', Carbon::now());
    }

    public function scopeAvailable($query)
    {
        return $query
            ->where('missions.state', 'Validée')
            ->where('missions.is_online', true)
            ->where('missions.is_registration_open', true)
            ->whereHas('structure', function (Builder $query) {
                $query->where('structures.state', 'Validée');
            });
    }

    public function scopeSimilarTo($query, Mission $mission)
    {
        if($mission->template_id) {
            $activityIds = collect([$mission->template?->activity_id, $mission->template?->activity_secondary_id])->filter()->all();
        } else {
            $activityIds = collect([$mission->activity_id, $mission->activity_secondary_id])->filter()->all();
        }

        return $query
            ->where('missions.id', '!=', $mission->id)
            ->where('missions.type', $mission->type)
            ->when($mission->type === 'Mission en présentiel', function ($query) use ($mission) {
                $query->where('missions.department', $mission->department);
            })
            // ->when(count($activityIds) > 0, function ($query) use ($activityIds) {
            //     $query->ofActivity($activityIds);
            // })
        ;
    }

    public function getIsAvailableForRegistrationAttribute()
    {
        return $this->state == 'Validée' && $this->is_online && $this->is_registration_open && $this->structure->state == 'Validée' && $this->has_places_left;
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

    public function scopeOfDomaine($query, ...$values)
    {
        return $query
            ->whereIn('domaine_id', $values)
            ->orWhereIn('domaine_secondary_id', $values)
            ->orWhereHas(
                'template',
                function (Builder $query) use ($values) {
                    $query
                        ->whereIn('domaine_id', $values)
                        ->orWhereIn('domaine_secondary_id', $values);
                }
            );
    }

    public function scopeOfTemplate($query, $template_id)
    {
        return $query
            ->where('template_id', $template_id);
    }

    public function scopeHasActivity($query, $value)
    {
        if ($value) {
            return $query
                ->whereNotNull('activity_id')
                ->orWhereNotNull('activity_secondary_id')
                ->orWhereHas(
                    'template',
                    function (Builder $query) {
                        $query->whereNotNull('activity_id');
                        $query->orWhereNotNull('activity_secondary_id');
                    }
                );
        } else {
            return $query
                ->whereNull('activity_id')
                ->orWhereNull('activity_secondary_id')
                ->orWhereHas(
                    'template',
                    function (Builder $query) {
                        $query->whereNull('activity_id');
                        $query->orWhereNull('activity_secondary_id');
                    }
                );
        }
    }

    public function scopeHasTemplate($query, $value)
    {
        if ($value) {
            return $query->whereNotNull('template_id');
        } else {
            return $query->whereNull('template_id');
        }
    }

    public function scopeHasCreneaux($query, $value)
    {
        if ($value) {
            return $query->whereNotNull('dates');
        } else {
            return $query->whereNull('dates');
        }
    }

    public function scopeOfActivity($query, ...$values)
    {
        return $query
            ->where(function ($query) use ($values) {
                $query
                    ->whereIn('activity_id', $values)
                    ->orWhereIn('activity_secondary_id', $values)
                    ->orWhereHas(
                        'template',
                        function (Builder $query) use ($values) {
                            $query->whereIn('activity_id', $values);
                            $query->orWhereIn('activity_secondary_id', $values);
                        }
                    );
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
            return $query->where(function (Builder $query) use ($territoire) {
                if($territoire->zips) {
                    foreach ($territoire->zips as $zip) {
                        $query->orWhereJsonContains('addresses', [['zip' => $zip]]);
                    }
                } else {
                    $query->where('id', null);
                }
            });
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
                return $query;
                break;
            case 'responsable':
                // Missions des structures dont je suis responsable
                return $query->where('structure_id', Auth::guard('api')->user()->contextable_id);
                break;
            case 'responsable_territoire':
                return $query->ofTerritoire(Auth::guard('api')->user()->contextable_id);
                break;
            case 'referent':
                // Missions qui sont dans mon département
                return $query
                    ->whereNotNull('department')
                    ->where('department', Auth::guard('api')->user()->departmentsAsReferent->first()->number);
                break;
            case 'referent_regional':
                // Missions qui sont dans ma région
                return $query
                    ->whereNotNull('department')
                    ->whereIn(
                        'department',
                        config('taxonomies.regions.departments')[Auth::guard('api')->user()->regionsAsReferent->first()->name]
                    );
                break;
            case 'tete_de_reseau':
                // Missions qui sont dans une structure rattachée à mon réseau
                return $query->ofReseau(Auth::guard('api')->user()->contextable_id);
                break;
            default:
                // Securite
                return $query->whereNull('id');
        }
    }

    public function scopeOfResponsable($query, $profile_id)
    {
        return $query->whereHas('responsables', function (Builder $query) use ($profile_id) {
            $query->where('responsable_id', $profile_id);
        });
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query->whereHas(
            'structure',
            function (Builder $query) use ($reseau_id) {
                $query->ofReseau($reseau_id);
            }
        );
    }

    public function scopeDistance($query, $latitude, $longitude)
    {
        $latName = 'latitude';
        $lonName = 'longitude';
        $query->select($this->getTable() . '.*');
        $sql = '((ACOS(SIN(? * PI() / 180) * SIN(' . $latName . ' * PI() / 180) + COS(? * PI() / 180) * COS(' .
            $latName . ' * PI() / 180) * COS((? - ' . $lonName . ') * PI() / 180)) * 180 / PI()) * 60 * ?) as distance';

        $query->selectRaw($sql, [$latitude, $latitude, $longitude, 1.1515 * 1.609344]);

        return $query;
    }

    public function duplicate()
    {
        $mission = $this->replicate();
        $mission->state = 'Brouillon';
        $mission->is_priority = false;

        $mission->save();

        if ($this->illustrations) {
            $values = $this->illustrations->pluck($this->illustrations, 'id')->map(
                function ($item) {
                    return ['field' => 'mission_illustrations'];
                }
            );
            $mission->illustrations()->sync($values);
        }

        if($this->has('responsables')) {
            $mission->responsables()->sync($this->responsables->pluck('id'));
        }

        return $mission;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(
                function ($mission) {
                    $mission->load('structure');

                    return !empty($mission->city)
                        ? "benevolat-{$mission->structure->name}-{$mission->city}"
                        : "benevolat-{$mission->structure->name}";
                }
            )
            ->saveSlugsTo('slug');
    }

    public function tags()
    {
        return $this->morphToMany(Term::class, 'termable')->wherePivot('field', 'mission_tags');
    }

    public function skills()
    {
        return $this->morphToMany(Term::class, 'termable')->wherePivot('field', 'mission_skills');
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
            ],
        ];
    }

    public function sendNotificationsTemoignages()
    {
        $participations = $this->participations()->state('Validée')->get();
        foreach ($participations as $participation) {
            /**
             * @var \App\Models\Participation $participation
             */
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

    public function getCommitmentAttribute()
    {
        return Utils::getCommitmentLabel(
            $this->commitment__duration,
            $this->commitment__time_period
        );
    }

    public function setCommitmentTotal()
    {
        $this->commitment__total = Utils::calculateCommitmentTotal(
            $this->commitment__duration,
            $this->commitment__time_period
        );
    }

    public function format()
    {
        $this->loadMissing(['template', 'template.domaine', 'template.domaineSecondary', 'domaine', 'domaineSecondary', 'structure.reseaux', 'responsables', 'activity', 'activitySecondary', 'template.activity', 'template.activitySecondary']);

        if ($this->template) {
            $domaines = collect([$this->template->domaine, $this->template->domaineSecondary]);
            $activities = collect([$this->template->activity, $this->template->activitySecondary]);
        } else {
            $domaines = collect([$this->domaine, $this->domaineSecondary]);
            $activities = collect([$this->activity, $this->activitySecondary]);
        }
        $domaines = $domaines->filter()->map(fn ($domaine) => ['id' => $domaine->id, 'name' => $domaine->name]);
        $activities = $activities->filter()->map(fn ($activity) => ['id' => $activity->id, 'name' => $activity->name]);

        $responsables = $this->responsables->map(fn ($responsable) => [
            'id' => $responsable->id,
            'first_name' => $responsable->first_name,
            'last_name' => $responsable->last_name,
            'email' => $responsable->email,
            'phone' => $responsable->phone,
            'mobile' => $responsable->mobile,
        ]);

        $firstAddress = $this->addresses ? $this->addresses[0] : null;
        $firstResponsable = $this->responsables ? $this->responsables[0] : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'state' => $this->structure->state,
            'type' => $this->type,
            'domaine' => $domaines->first(),
            'domaines' => $domaines->values(),
            'activity' => $activities->first(),
            'activities' => $activities->values(),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'commitment' => [
                'time_period' => $this->commitment__time_period,
                'duration' => $this->commitment__duration,
                'total_hours_per_year' => $this->commitment__total,
            ],
            'description' => $this->description,
            'objectifs' => $this->objectif,
            'participations_max' => $this->participations_max,
            'places_left' => $this->places_left,
            'url' => $this->full_url,
            'is_snu_mig_compatible' => $this->is_snu_mig_compatible,
            'snu_mig_places' => $this->snu_mig_places,
            'picture' => $this->picture,
            // 'is_autonomy' => $this->is_autonomy,
            // 'autonomy_zips' => $this->is_autonomy && count($this->autonomy_zips) > 0 ? $this->autonomy_zips : null,
            'addresses' => $this->addresses,
            'address' => $firstAddress,
            // 'address' => [
            //     'full' => $this->full_address,
            //     'address' => $this->address,
            //     'zip' => $this->zip,
            //     'city' => $this->city,
            //     'department' => $this->department,
            //     'country' => $this->country,
            //     'latitude' => $this->latitude,
            //     'longitude' => $this->longitude,
            // ],
            'structure' => $this->structure ? [
                'id' => $this->structure->id,
                'name' => $this->structure->name,
                'rna' => $this->structure->rna,
                'api_id' => $this->structure->api_id,
                'state' => $this->structure->state,
                'reseaux' => $this->structure->reseaux->count() ? $this->structure->reseaux->all() : null,
            ] : null,
            'responsables' => $responsables->values(),
            'responsable' => $firstResponsable ? [
                'id' => $firstResponsable->id,
                'first_name' => $firstResponsable->first_name,
                'last_name' => $firstResponsable->last_name,
                'email' => $firstResponsable->email,
                'phone' => $firstResponsable->phone,
                'mobile' => $firstResponsable->mobile,
            ] : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function setStartDateAttribute($value)
    {
        if(!$value) {
            $this->attributes['start_date'] = null;
        } else {
            $this->attributes['start_date'] = \Carbon\Carbon::parse($value)
                ->timezone('Europe/Paris')
                ->toDateTimeString();
        }
    }

    public function setEndDateAttribute($value)
    {
        if(!$value) {
            $this->attributes['end_date'] = null;
        } else {
            $this->attributes['end_date'] = \Carbon\Carbon::parse($value)
                ->timezone('Europe/Paris')
                ->toDateTimeString();
        }
    }
}
