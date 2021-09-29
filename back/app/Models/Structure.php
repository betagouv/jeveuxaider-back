<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\Utils;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Tag;

class Structure extends Model implements HasMedia
{
    use SoftDeletes, LogsActivity, HasRelationships, HasTags, InteractsWithMedia, HasSlug;

    const CEU_TYPES = [
        "SDIS (Service départemental d'Incendie et de Secours)",
        'Gendarmerie',
        'Police',
        'Armées'
    ];

    protected $table = 'structures';

    protected $fillable = [
        'name',
        'user_id',
        'siret',
        'statut_juridique',
        'association_types',
        'structure_publique_type',
        'structure_publique_etat_type',
        'structure_privee_type',
        'description',
        'address',
        'latitude',
        'longitude',
        'zip',
        'city',
        'department',
        'country',
        'website',
        'facebook',
        'twitter',
        'instagram',
        'donation',
        'state',
        'publics_beneficiaires',
        'image_1',
        'image_2',
        'api_id',
        'rna',
        'phone',
        'email',
        'slug',
        'color',
        'send_volunteer_coordonates',
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France',
    ];

    protected $casts = [
        'is_reseau' => 'boolean',
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'publics_beneficiaires' => 'array',
        'send_volunteer_coordonates' => 'boolean',
    ];

    protected $hidden = ['media'];

    protected $appends = ['full_url', 'full_address', 'domaines', 'logo', 'places_left', 'override_image_1', 'override_image_2'];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function getFullUrlAttribute()
    {
        return "/organisations/$this->slug";
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
                break;
            case 'responsable':
                return $query->whereHas('responsables', function (Builder $query) {
                    $query->where('profile_id', Auth::guard('api')->user()->profile->id);
                });
                break;
            case 'referent':
                return $query
                    ->whereNotNull('department')
                    ->where('department', Auth::guard('api')->user()->profile->referent_department);
                break;
            case 'referent_regional':
                return $query
                    ->whereNotNull('department')
                    ->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
                break;
            // case 'superviseur':
            //     return $query
            //         ->whereNotNull('reseau_id')
            //         ->where('reseau_id', Auth::guard('api')->user()->profile->reseau->id);
            //     break;
            case 'tete_de_reseau':
                return $query->whereHas('reseaux', function (Builder $query) {
                    $query->where('reseaux.id', Auth::guard('api')->user()->profile->teteDeReseau->id);
                });
        }
    }

    public function getDomainesAttribute()
    {
        return $this->tagsWithType('domaine')->values();
    }

    public function getDomainesWithImageAttribute()
    {
        return Tag::whereIn('id', $this->tagsWithType('domaine')->pluck('id'))->get()->toArray();
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
        $this->attributes['structure_publique_type'] = ($this->statut_juridique == 'Structure publique') ? $value : null;
    }

    public function setStructurePubliqueEtatTypeAttribute($value)
    {
        $this->attributes['structure_publique_etat_type'] = ($this->statut_juridique == 'Structure publique') ? $value : null;
    }

    public function setStructurePriveeTypeAttribute($value)
    {
        $this->attributes['structure_privee_type'] = ($this->statut_juridique == 'Structure privée') ? $value : null;
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

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('missions', function (Builder $query) use ($domain_id) {
                $query->where('domaine_id', $domain_id)
                    ->orWhereHas('tags', function (Builder $query) use ($domain_id) {
                        $query->where('id', $domain_id);
                    });
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
        if (!isset($this->attributes['structure_publique_etat_type'])) {
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

    public function reseau()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function reseaux()
    {
        return $this->belongsToMany(Reseau::class);
    }

    public function members()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->withPivot('role');
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function responsables()
    {
        return $this->belongsToMany('App\Models\Profile', 'members')->wherePivot('role', 'responsable');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission');
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

    public function secondariesDomainesFromMissions()
    {
        return $this->hasManyDeep(
            'App\Models\Tag',
            ['App\Models\Mission', 'taggables'],
            [null, ['taggable_type', 'taggable_id']]
        );
    }

    public function addMember(Profile $profile, $role)
    {
        return $this->members()->attach($profile, ['role' => $role]);
    }

    public function deleteMember(Profile $profile)
    {
        $this->members()->detach($profile);

        $user = User::find($profile->user_id);

        if ($user->context_role == 'responsable') {
            $user->context_role = null;
            $user->save();
        }

        $this->resetResponsable($profile);

        return $this->load('members');
    }

    public function resetResponsable(Profile $profile)
    {
        $newResponsableProfileId = $this->members->where('id', '!=', $profile->id)->pluck('id')->first();
        if ($newResponsableProfileId) {
            Mission::where('responsable_id', $profile->id)->update(['responsable_id' => $newResponsableProfileId]);
        }
    }

    public function addMission($values)
    {
        $mission = $this->missions()->create($values);

        if (!empty($values['tags'])) {
            $mission->syncTagsWithType($values['tags'], 'domaine');
        }

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
        $avgResponseTime = $this->conversations->avg('response_time');
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

    // TEMP LARAVEL 7. DISPO DANS LARAVEL 8
    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }

    public function getLogoAttribute()
    {
        return $this->getMediaUrls('logo');
    }

    protected function getMediaUrls($field)
    {
        $media = $this->getFirstMedia('structures', ['field' => $field]);
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->width(640)
            ->nonQueued()
            ->performOnCollections('structures');

        $this->addMediaConversion('thumb')
            ->width(320)
            ->nonQueued()
            ->performOnCollections('structures');

        $this->addMediaConversion('xxl')
            ->width(1440)
            ->nonQueued()
            ->performOnCollections('structures');
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['id', 'name'])
            ->saveSlugsTo('slug');
    }

    public function getPlacesLeftAttribute()
    {
        return $this->missions()->available()->get()->sum('places_left');
    }

    public function getOverrideImage1Attribute()
    {
        return $this->getMediaUrls('override_image_1');
    }

    public function getOverrideImage2Attribute()
    {
        return $this->getMediaUrls('override_image_2');
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

    public function getCompletionRateAttribute()
    {
        $fields = [
            ['name' => 'rna', 'label' => 'RNA'],
            ['name' => 'logo', 'label' => 'Logo'],
            ['name' => 'email', 'label' => "E-mail public de l'organisation"],
            ['name' => 'phone', 'label' => "Téléphone de l'organisation"],
            ['name' => 'website', 'label' => "Site de l'organisation"],
            ['name' => 'facebook', 'label' => "Page Facebook"],
            ['name' => 'twitter', 'label' => "Page Twitter"],
            ['name' => 'instagram', 'label' => "Profil Instagram"],
        ];
        $existingFieldsCount = 0;
        $missingFields = [];

        foreach ($fields as $field) {
            if ($this->{$field['name']}) {
                $existingFieldsCount++;
            } else {
                $missingFields[] = $field;
            }
        }

        return [
            'score' => round($existingFieldsCount / count($fields) * 100),
            'missing_fields' => $missingFields
        ];
    }

}
