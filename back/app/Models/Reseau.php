<?php

namespace App\Models;

use Staudenmeir\EloquentHasManyDeep\HasRelationships;
use Illuminate\Database\Eloquent\Model;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\InteractsWithMedia;

class Reseau extends Model implements HasMedia
{
    use HasRelationships, HasTags, InteractsWithMedia;

    protected $table = 'reseaux';

    protected $fillable = [
        'name',
        'publics_beneficiaires',
        'created_at',
        'description',
        'email',
        'phone',
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
        'image_1',
        'image_2',
    ];

    protected $appends = ['domaines', 'full_address'];

    protected $casts = [
        'publics_beneficiaires' => 'array',
    ];


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
        return $this->hasManyDeep('App\Models\Mission', ['reseau_structure', 'App\Models\Structure']);
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
        Activity::where('subject_type', 'App\Models\Structure')
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

    public function getDomainesAttribute()
    {
        return $this->tagsWithType('domaine')->values();
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->zip} {$this->city}";
    }

    public function getLogoAttribute()
    {
        return $this->getMediaUrls('logo');
    }

    protected function getMediaUrls($field)
    {
        $media = $this->getFirstMedia('reseaux', ['field' => $field]);
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                // $mediaUrls[$key] = $media->getUrl($key);
                $mediaUrls[$key] = $media->getSrcset($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(320)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('reseaux');

        $this->addMediaConversion('large')
            ->width(640)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('reseaux');

        $this->addMediaConversion('xxl')
            ->width(1440)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('reseaux');
    }
}
