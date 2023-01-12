<?php

namespace App\Models;

use App\Helpers\Utils;
use App\Models\Media as ModelMedia;
use App\Traits\HasMissingFields;
use App\Traits\Notable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;

class Structure extends Model implements HasMedia
{
    use SoftDeletes, LogsActivity, HasRelationships, HasTags, InteractsWithMedia, HasSlug, HasMissingFields, Searchable, Notable;

    const CEU_TYPES = [
        "SDIS (Service départemental d'Incendie et de Secours)",
        'Gendarmerie',
        'Police',
        'Armées',
    ];

    protected $table = 'structures';

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'state' => 'Brouillon',
        'country' => 'France',
    ];

    protected $casts = [
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'publics_beneficiaires' => 'array',
        'send_volunteer_coordonates' => 'boolean',
        'is_alsace_moselle' => 'boolean',
        'missing_fields' => 'array',
    ];

    protected $hidden = ['media'];

    protected $appends = ['full_url', 'full_address'];

    public function shouldBeSearchable()
    {
        return $this->state == 'Validée' ? true : false;
    }

    public function searchableAs()
    {
        return config('scout.prefix').'_covid_organisations';
    }

    public function getCheckFieldsAttribute()
    {
        switch ($this->statut_juridique) {
            case 'Collectivité':
                return ['name', 'zip', 'city', 'department', 'domaines'];
            case 'Organisation publique':
                return ['name', 'zip', 'city', 'department', 'domaines', 'publics_beneficiaires'];
            default:
                return ['name', 'zip', 'city', 'department', 'domaines', 'publics_beneficiaires', 'description'];
        }
    }

    public function getFullUrlAttribute()
    {
        return "/organisations/$this->slug";
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function scopeRole($query, $contextRole)
    {
        $user = Auth::guard('api')->user();

        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'responsable':
                return $query->whereHas('members', function (Builder $query) use ($user) {
                    $query->where('user_id', $user->id);
                });
                break;
            case 'referent':
                return $query
                    ->whereNotNull('department')
                    ->where('department', $user->departmentsAsReferent->first()->number);
                break;
            case 'referent_regional':
                return $query
                    ->whereNotNull('department')
                    ->whereIn('department', config('taxonomies.regions.departments')[$user->regionsAsReferent->first()->name]);
                break;
            case 'tete_de_reseau':
                return $query->ofReseau($user->contextable_id);
                break;
            case 'responsable_territoire':
                return $query->ofTerritoire($user->contextable_id);
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Utils::ucfirst($value);
    }

    public function setAssociationTypesAttribute($value)
    {
        $this->attributes['association_types'] = ($this->statut_juridique == 'Association') ? json_encode($value) : null;
    }

    public function setStructurePubliqueTypeAttribute($value)
    {
        $this->attributes['structure_publique_type'] = ($this->statut_juridique == 'Organisation publique') ? $value : null;
    }

    public function setStructurePubliqueEtatTypeAttribute($value)
    {
        $this->attributes['structure_publique_etat_type'] = ($this->statut_juridique == 'Organisation publique') ? $value : null;
    }

    public function setStructurePriveeTypeAttribute($value)
    {
        $this->attributes['structure_privee_type'] = ($this->statut_juridique == 'Organisation privée') ? $value : null;
    }

    public function setStatutJuridiqueAttribute($value)
    {
        switch ($value) {
            case 'loi1901':
                $value = 'Association';
                break;
            case 'collectivite':
                $value = 'Collectivité';
                break;
            case 'alsaceMoselle':
                $value = 'Association';
                break;
        }

        $this->attributes['statut_juridique'] = $value;
    }

    public function scopeCeu($query, $value)
    {
        if ($value) {
            return $query
                ->whereIn('structure_publique_etat_type', self::CEU_TYPES);
        }

        return $query
            ->whereNull('structure_publique_etat_type')
            ->orWhereNotIn('structure_publique_etat_type', self::CEU_TYPES);
    }

    public function scopeDepartment($query, $value)
    {
        return $query
            ->where('department', $value);
    }

    public function scopeRegion($query, $value)
    {
        return $query
            ->whereIn('department', config('taxonomies.regions.departments')[$value]);
    }

    public function scopeOfDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('missions', function (Builder $query) use ($domain_id) {
                $query->ofDomaine($domain_id);
            });
    }

    public function territoire()
    {
        return $this->hasOne('App\Models\Territoire');
    }

    public function scopeOfTerritoire($query, $territoire_id)
    {
        $territoire = Territoire::find($territoire_id);

        if ($territoire->type == 'department') {
            return $query
                ->where('department', $territoire->department);
        }

        if ($territoire->type == 'collectivity') {
            return $query
                ->whereIn('zip', $territoire->zips);
        }

        if ($territoire->type == 'city') {
            return $query
                ->whereIn('zip', $territoire->zips);
        }
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        return $query->whereHas('reseaux', function (Builder $query) use ($reseau_id) {
            $query->where('reseaux.id', $reseau_id);
        });
    }

    public function scopeValidated($query)
    {
        return $query->where('state', 'Validée');
    }

    public function getCeuAttribute()
    {
        if (! isset($this->attributes['structure_publique_etat_type'])) {
            return false;
        }

        return in_array($this->attributes['structure_publique_etat_type'], self::CEU_TYPES) ? true : false;
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->zip} {$this->city}";
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function reseaux()
    {
        return $this->belongsToMany(Reseau::class);
    }

    public function members()
    {
        return $this->morphToMany(User::class, 'rolable', 'rolables')->withPivot('fonction');
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function OldResponsables()
    {
        return $this->belongsToMany('App\Models\Profile', 'old_members')->wherePivot('role', 'responsable');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
    }

    public function missionsAvailable()
    {
        return $this->hasMany('App\Models\Mission')->where('state', 'Validée');
    }

    public function participations()
    {
        return $this->hasManyThrough('App\Models\Participation', 'App\Models\Mission');
    }

    public function waitingParticipations()
    {
        return $this->hasManyThrough('App\Models\Participation', 'App\Models\Mission')
            ->where('participations.state', 'En attente de validation');
    }

    public function conversations()
    {
        return $this->hasManyDeep(
            'App\Models\Conversation',
            ['App\Models\Mission', 'App\Models\Participation'],
            [null, null, ['conversable_type', 'conversable_id']]
        );
    }

    public function domaines()
    {
        return $this->morphToMany(Domaine::class, 'domainable')->wherePivot('field', 'structure_domaines');
    }

    public function addMember(User $user, $fonction = null)
    {
        return $user->assignRole('responsable', $this, $fonction);
    }

    public function deleteMember(User $user)
    {
        $this->members()->detach($user);

        $user->resetContextRole();
        $user->save();

        return $this->load('members');
    }

    // public function resetResponsable(Profile $profile)
    // {
    //     $newResponsableProfileId = $this->members->where('id', '!=', $profile->id)->pluck('id')->first();
    //     if ($newResponsableProfileId) {
    //         Mission::where('responsable_id', $profile->id)->where('structure_id', $this->id)->update(['responsable_id' => $newResponsableProfileId]);
    //     }
    // }

    public function addMission($values)
    {
        $mission = $this->missions()->create($values);

        return $mission;
    }

    public function setResponseRatio()
    {
        $participationsCount = $this->participations->count();
        $waitingParticipationsCount = $this->participations->where('state', 'En attente de validation')->count();
        $this->response_ratio = round(($participationsCount - $waitingParticipationsCount) / $participationsCount * 100);

        return $this;
    }

    public function setResponseTime()
    {
        $avgResponseTime = $this->conversations->where('created_at', '>=', Carbon::now()->subMonth(3)->toDateTimeString())->avg('response_time');
        if ($avgResponseTime) {
            $this->response_time = intval($avgResponseTime);
        }

        return $this;
    }

    public function getResponseTimeScoreAttribute()
    {
        // Response time ( note sur 100 )
        // 1 jour = 100 - ( 100 * 1 /10  )
        // 2 jours = 100 - ( 100 * 2 / 10 )
        // ...
        // 10 jours = 0

        // Dans le cas d'une nouvelle orga, le responseTime est null on met donc un score arbitraire 50
        // Dans le cas d'une orga inactive après janvier 2021, le responseTime est null on met donc un score arbitraire 50
        if ($this->response_time == null) {
            return 50;
        }
        $responseTime = $this->response_time / 86400;
        $scoreResponseTime = round(100 - (100 * $responseTime / 10));

        return $scoreResponseTime > 0 ? $scoreResponseTime : 0;
    }

    public function getScoreAttribute()
    {
        if ($this->response_time == null) {
            return 50;
        }

        $scoreResponseTime = round(100 - (100 * ($this->response_time / 60*60*24)) / 10);
        $scoreResponseTime = $scoreResponseTime > 0 ? $scoreResponseTime : 0;

        $pointsResponseTime = $scoreResponseTime * 0.7;
        $pointsResponseRatio = $this->response_ratio * 0.3;

        $score = $pointsResponseTime + $pointsResponseRatio;

        return $score <= 100 ? $score : 100;
    }

    public function logo()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'structure__logo');
    }

    public function illustrations()
    {
        return $this->morphToMany(ModelMedia::class, 'mediable')->wherePivot('field', 'organisation_illustrations');
    }

    public function overrideImage1()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'structure__override_image_1');
    }

    public function overrideImage2()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'structure__override_image_2');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Logo
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('structure__logo');
        $this->addMediaConversion('small')
            ->width(240)
            ->height(96)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('structure__logo');
        $this->addMediaConversion('large')
            ->height(240)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('structure__logo');

        // Illustrations overrides
        $this->addMediaConversion('large')
            ->height(900)
            ->crop(Manipulations::CROP_CENTER, 1400, 900)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('structure__override_image_1', 'structure__override_image_2');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['id', 'name'])
            ->saveSlugsTo('slug');
    }

    public function getPlacesLeftAttribute()
    {
        return $this->missions()->available()->sum('places_left');
    }

    public function getPlacesOfferedAttribute()
    {
        return $this->missions()->available()->sum('participations_max');
    }

    public function canBeSendToApiEngagement()
    {
        if (config('app.env') != 'production') {
            return false;
        }

        return $this->state == 'Validée' && $this->statut_juridique == 'Association'
            && $this->rna && $this->rna != 'N/A'
            && $this->api_id && $this->api_id != 'N/A';
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value),
        );
    }

    protected function activities(): Attribute
    {
        return Attribute::make(
            get: function () {
                $activitiesThroughMissions = Mission::ofStructure($this->id)->where('state', 'Validée')->whereHas('activity')->get()->map(fn ($mission) => $mission->activity_id)->toArray();
                $activitiesThroughTemplates = Mission::ofStructure($this->id)->where('state', 'Validée')->whereHas('template.activity')->get()->map(fn ($mission) => $mission->template->activity_id)->toArray();
                $activitiesMergedIds = array_unique(array_merge($activitiesThroughMissions, $activitiesThroughTemplates));

                return Activity::whereIn('id', $activitiesMergedIds)->get();
            },
        );
    }

    // public function makeAllSearchableUsing(Builder $query)
    // {
    //     return $query->with(['reseaux', 'domaines', 'illustrations', 'overrideImage1'])->withCount(['missionsAvailable']);
    // }

    // ALGOLIA
    public function toSearchableArray()
    {
        $this->load(['reseaux', 'domaines', 'illustrations', 'overrideImage1']);
        $this->loadCount(['missionsAvailable']);

        $publicsBeneficiaires = config('taxonomies.mission_publics_beneficiaires.terms');

        $organisation = [
            'id' => $this->id,
            'rna' => $this->rna,
            'slug' => $this->slug,
            'picture' => $this->picture,
            'api_id' => $this->api_id,
            'name' => $this->name,
            'state' => $this->state,
            'phone' => $this->phone,
            'email' => $this->email,
            'url' => $this->full_url,
            'description' => $this->description,
            'statut_juridique' => $this->statut_juridique,
            'is_alsace_moselle' => $this->is_alsace_moselle,
            'association_types' => $this->association_types,
            'structure_publique_type' => $this->structure_publique_type,
            'structure_publique_etat_type' => $this->structure_publique_etat_type,
            'structure_privee_type' => $this->structure_privee_type,
            'full_address' => $this->full_address,
            'address' => $this->address,
            'zip' => $this->zip,
            'city' => $this->city,
            'department' => $this->department,
            'country' => $this->country,
            'department_name' => $this->department && isset(config('taxonomies.departments.terms')[$this->department]) ? $this->department.' - '.config('taxonomies.departments.terms')[$this->department] : null,
            'website' => $this->website,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'donation' => $this->donation,
            'response_ratio' => $this->response_ratio,
            'response_time' => $this->response_time,
            'created_at' => $this->created_at,
            'publics_beneficiaires' => is_array($this->publics_beneficiaires) ? array_map(function ($public) use ($publicsBeneficiaires) {
                return $publicsBeneficiaires[$public];
            }, $this->publics_beneficiaires) : null,
            'domaines' => $this->domaines ? $this->domaines->map(function ($domaine) {
                return [
                    'id' => $domaine->id,
                    'name' => $domaine->name,
                ];
            })->all() : null,
            'activities' => $this->activities ? $this->activities->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'name' => $activity->name,
                ];
            })->all() : null,
            'reseaux' => $this->reseaux ? $this->reseaux->map(function ($reseau) {
                return [
                    'id' => $reseau->id,
                    'name' => $reseau->name,
                ];
            })->all() : null,
            'missions_available_count' => $this->missions_available_count,
        ];

        if ($this->latitude && $this->longitude) {
            $organisation['_geoloc'] = [
                'lat' => $this->latitude,
                'lng' => $this->longitude,
            ];
        }

        return $organisation;
    }

    public function getPictureAttribute()
    {
        if ($this->overrideImage1) {
            return $this->overrideImage1->urls;
        }

        return $this->illustrations->first() ? $this->illustrations->first()->urls : null;
    }

    // ENDPOINT
    public function format()
    {
        return [
            'id' => $this->id,
            'rna' => $this->rna,
            'api_id' => $this->api_id,
            'name' => $this->name,
            'picture' => $this->picture,
            'state' => $this->state,
            'phone' => $this->phone,
            'email' => $this->email,
            'url' => $this->full_url,
            'description' => $this->description,
            'statut_juridique' => $this->statut_juridique,
            'is_alsace_moselle' => $this->is_alsace_moselle,
            'association_types' => $this->association_types,
            'structure_publique_type' => $this->structure_publique_type,
            'structure_publique_etat_type' => $this->structure_publique_etat_type,
            'structure_privee_type' => $this->structure_privee_type,
            'address' => [
                'full' => $this->full_address,
                'address' => $this->address,
                'zip' => $this->zip,
                'city' => $this->city,
                'department' => $this->department,
                'country' => $this->country,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ],
            'website' => $this->website,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'instagram' => $this->instagram,
            'donation' => $this->donation,
            'publics_beneficiaires' => $this->publics_beneficiaires,
            'domaines' => $this->domaines ? $this->domaines->map(function ($domaine) {
                return [
                    'id' => $domaine->id,
                    'name' => $domaine->name,
                ];
            })->all() : null,
            'reseaux' => $this->reseaux ? $this->reseaux->map(function ($reseau) {
                return [
                    'id' => $reseau->id,
                    'name' => $reseau->name,
                ];
            })->all() : null,
            'responsables' => $this->members ? $this->members->map(function ($user) {
                return [
                    'id' => $user->profile->id,
                    'first_name' => $user->profile->first_name,
                    'last_name' => $user->profile->last_name,
                    'email' => $user->profile->email,
                    'phone' => $user->profile->phone,
                    'mobile' => $user->profile->mobile,
                ];
            })->all() : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }


    public function getPermissionsAttribute()
    {
        return [
            'canUpdate' =>  Auth::guard('api')->user() ? Auth::guard('api')->user()->can('update', $this) : false,
            'canChangeState' =>  Auth::guard('api')->user() ? Auth::guard('api')->user()->can('changeState', $this) : false,
        ];
    }
}
