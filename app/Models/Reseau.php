<?php

namespace App\Models;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Image\Manipulations;
use App\Models\Media as ModelMedia;
use App\Traits\HasMissingFields;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Builder;

class Reseau extends Model implements HasMedia
{
    use HasRelationships;
    use HasTags;
    use InteractsWithMedia;
    use HasSlug;
    use LogsActivity;
    use HasMissingFields;

    protected $table = 'reseaux';

    protected $guarded = [
        'id',
    ];

    protected $appends = ['full_address', 'full_url'];

    protected $casts = [
        'publics_beneficiaires' => 'array',
        'is_published' => 'boolean',
    ];

    protected $checkFields = ['logo', 'description', 'phone', 'email', 'address', 'zip', 'city', 'department', 'website', 'domaines'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function responsables()
    {
        return $this->hasMany(Profile::class, 'tete_de_reseau_id');
    }

    public function structures()
    {
        return $this->belongsToMany(Structure::class);
    }

    public function missions()
    {
        return $this->hasManyDeep(Mission::class, ['reseau_structure', Structure::class]);
    }

    public function participations()
    {
        return $this->hasManyDeepFromRelations($this->missions(), (new Mission)->participations());
    }

    public function getParticipationsMaxAttribute()
    {
        return $this->missions()->sum('participations_max');
    }

    public function missionTemplates()
    {
        return $this->hasMany(MissionTemplate::class);
    }

    public function invitationsResponsables()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable')->where('role', 'responsable_reseau');
    }

    public function invitationsAntennes()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable')->where('role', 'responsable_antenne');
    }

    public function deleteResponsable(Profile $profile)
    {
        $profile->tete_de_reseau_id = null;
        $profile->save();

        $profile->user->resetContextRole();
        $profile->user->save();

        return $this->load('responsables');
    }

    public function createStructure(string $name, User $user, array $attributes = [])
    {
        $attributes = array_merge([
            'name' => $name,
            'user_id' => $user->id,
            'statut_juridique' => 'Association',
        ], $attributes);

        $structure = Structure::create($attributes);

        $this->structures()->attach($structure->id);

        // UPDATE LOG
        ActivityLog::where('subject_type', 'App\Models\Structure')
            ->where('subject_id', $structure->id)
            ->where('description', 'created')
            ->update(
                [
                    'causer_id' => $user->id,
                    'causer_type' => 'App\Models\User',
                    'data' => [
                        "subject_title" => $structure->name,
                        "full_name" => $user->profile->full_name,
                        "causer_id" => $user->profile->id,
                        "context_role" => 'responsable'
                    ]
                ]
            );

        return $structure;
    }

    public function domaines()
    {
        return $this->morphToMany(Domaine::class, 'domainable')->wherePivot('field', 'reseau_domaines');
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->zip} {$this->city}";
    }

    public function logo()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'reseau__logo');
    }

    public function illustrations()
    {
        return $this->morphToMany(ModelMedia::class, 'mediable')->wherePivot('field', 'reseau_illustrations');
    }

    public function overrideImage1()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'reseau__override_image_1');
    }

    public function overrideImage2()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'reseau__override_image_2');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // Logo
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('reseau__logo');
        $this->addMediaConversion('small')
            ->height(112)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('reseau__logo');
        $this->addMediaConversion('large')
            ->height(240)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('reseau__logo');

        // Illustrations overrides
        $this->addMediaConversion('large')
            ->height(900)
            ->crop(Manipulations::CROP_CENTER, 1400, 900)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('reseau__override_image_1', 'reseau__override_image_2');
    }

    public function getFullUrlAttribute()
    {
        return "/reseaux/$this->slug";
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['id', 'name'])
            ->saveSlugsTo('slug');
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value),
        );
    }

    public function scopeOfDomaine($query, $domain_id)
    {
        return $query
            ->whereHas('domaines', function (Builder $query) use ($domain_id) {
                $query->where('id',$domain_id);
            });
    }

    public function getPlacesLeftAttribute()
    {
        return Mission::available()->ofReseau($this->id)->sum('places_left');
    }
}
