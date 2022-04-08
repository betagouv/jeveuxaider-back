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
use App\Traits\HasMissingFields;
use Spatie\Image\Manipulations;
use App\Models\Media as ModelMedia;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Structure extends Model implements HasMedia
{
    use SoftDeletes, LogsActivity, HasRelationships, HasTags, InteractsWithMedia, HasSlug, HasMissingFields;

    const CEU_TYPES = [
        "SDIS (Service départemental d'Incendie et de Secours)",
        'Gendarmerie',
        'Police',
        'Armées'
    ];

    protected $table = 'structures';

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'state' => 'En attente de validation',
        'country' => 'France',
    ];

    protected $casts = [
        'association_types' => 'array',
        'latitude' => 'float',
        'longitude' => 'float',
        'publics_beneficiaires' => 'array',
        'send_volunteer_coordonates' => 'boolean',
        'missing_fields' => 'array'
    ];

    protected $hidden = ['media'];

    protected $checkFields = ['description', 'domaines', 'publics_beneficiaires', 'website'];

    protected $appends = ['full_url', 'full_address'];

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
        $user =  Auth::guard('api')->user();

        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
                break;
            case 'responsable':
                return $query->whereHas('responsables', function (Builder $query) use ($user) {
                    $query->where('profile_id', $user->profile->id);
                });
                break;
            case 'referent':
                return $query
                    ->whereNotNull('department')
                    ->where('department', $user->profile->referent_department);
                break;
            case 'referent_regional':
                return $query
                    ->whereNotNull('department')
                    ->whereIn('department', config('taxonomies.regions.departments')[$user->profile->referent_region]);
                break;
            case 'tete_de_reseau':
                return $query->ofReseau($user->profile->tete_de_reseau_id);
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

    public function scopeDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('missions', function (Builder $query) use ($domain_id) {
                $query->domaine($domain_id);
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

    // public function reseau()
    // {
    //     return $this->belongsTo('App\Models\Structure');
    // }

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

    public function domaines()
    {
        return $this->morphToMany(Domaine::class, 'domainable')->wherePivot('field', 'structure_domaines');
    }

    public function addMember(Profile $profile, $role)
    {
        return $this->members()->attach($profile, ['role' => $role]);
    }

    public function deleteMember(Profile $profile)
    {
        $this->members()->detach($profile);

        $user = User::find($profile->user_id);

        $user->resetContextRole();
        $user->save();

        $this->resetResponsable($profile);

        return $this->load('members');
    }

    public function resetResponsable(Profile $profile)
    {
        $newResponsableProfileId = $this->members->where('id', '!=', $profile->id)->pluck('id')->first();
        if ($newResponsableProfileId) {
            Mission::where('responsable_id', $profile->id)->where('structure_id', $this->id)->update(['responsable_id' => $newResponsableProfileId]);
        }
    }

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
            ->height(112)
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
        return $this->missions()->available()->get()->sum('places_left');
    }

    public function getPlacesOfferedAttribute()
    {
        return $this->missions()->available()->get()->sum('participations_max');
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

    // ALGOLIA
    public function toSearchableArray()
    {
        $this->load(['reseaux', 'domaines']);

        return [
            'id' => $this->id,
            'rna' => $this->rna,
            'api_id' => $this->api_id,
            'name' => $this->name,
            'state' => $this->state,
            'phone' => $this->phone,
            'email' => $this->email,
            'url' => $this->full_url,
            'description' => $this->description,
            'statut_juridique' => $this->statut_juridique,
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
            'created_at' => $this->created_at,
            'publics_beneficiaires' => $this->publics_beneficiaires,
            'domaines' => $this->domaines ? $this->domaines->map(function($domaine){
                return $domaine['name'];
            })->all() : null,
            'reseaux' => $this->reseaux ? $this->reseaux->all() : null,
        ];
    }

    public function getPictureAttribute()
    {

        if($this->overrideImage1){
            return $this->overrideImage1->urls;
        }

        return $this->illustrations->first() ? $this->illustrations->first()->urls : null;
    }

    // ENDPOINT
    public function format() {
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
            'domaines' => $this->domaines ? $this->domaines->map(function($domaine){
                return [
                    'id' => $domaine->id,
                    'name' => $domaine->name,
                ];
            })->all() : null,
            'reseaux' => $this->reseaux ? $this->reseaux->map(function($reseau){
                return [
                    'id' => $reseau->id,
                    'name' => $reseau->name,
                ];
            })->all() : null,
            'responsables' => $this->members ? $this->members->map(function($responsable){
                return [
                    'id' => $responsable->id,
                    'first_name' => $responsable->first_name,
                    'last_name' => $responsable->last_name,
                    'email' => $responsable->email,
                ];
            })->all() : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
