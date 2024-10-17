<?php

namespace App\Models;

use App\Models\Media as ModelMedia;
use App\Traits\HasMetatags;
use Illuminate\Database\Eloquent\Model;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Domaine extends Model implements HasMedia
{
    use InteractsWithMedia;
    use HasSlug;
    use HasMetatags;

    protected $table = 'domaines';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'published' => 'boolean',
        'faq' => 'json',
    ];

    protected $attributes = [
        'published' => false,
    ];

    protected $appends = ['full_url'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($domaine) {
                return $domaine->name;
            })
            ->saveSlugsTo('slug');
    }

    public function getFullUrlAttribute()
    {
        return "/domaines-action/$this->slug";
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // 2x for high pixel density

        // Banner
        $this->addMediaConversion('card')
            ->fit(Manipulations::FIT_CROP, 600, 286)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__banner');
        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 1680, 1400)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__banner');
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 600, 500)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__banner');

        // Illustrations
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 400, 400)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations');
        $this->addMediaConversion('carousel')
            ->fit(Manipulations::FIT_CROP, 860, 860)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations');

        // Logos partenaires
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__logos_partenaires', 'domaine__logos_partenaires_actifs');
        $this->addMediaConversion('small')
            ->height(112)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__logos_partenaires', 'domaine__logos_partenaires_actifs');

        // Illustrations mission
        $this->addMediaConversion('card')
            ->fit(Manipulations::FIT_CROP, 600, 286)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_mission');
        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 1300, 620)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_mission');
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 576, 274)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_mission');

        // Illustrations organisation
        $this->addMediaConversion('large')
            ->height(900)
            ->crop(Manipulations::CROP_CENTER, 1400, 900)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_organisation');
    }

    public function missionTemplates()
    {
        return $this->hasMany(MissionTemplate::class);
    }

    public function banner()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'domaine__banner');
    }

    public function illustrations()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'domaine__illustrations');
    }

    public function illustrationsOrganisation()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'domaine__illustrations_organisation');
    }

    public function illustrationsMission()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'domaine__illustrations_mission');
    }

    public function logosPartenaires()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'domaine__logos_partenaires');
    }

    public function logosPartenairesActifs()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'domaine__logos_partenaires_actifs');
    }

    public function getPlacesLeftAttribute()
    {
        return Mission::available()->ofDomaine($this->id)->sum('places_left');
    }
}
