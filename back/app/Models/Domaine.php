<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Media as ModelMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Image\Manipulations;

class Domaine extends Model implements HasMedia
{
    use InteractsWithMedia, HasSlug;

    protected $table = 'domaines';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'published' => 'boolean',
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
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 470, 224)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__banner');

        // Illustrations
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 400, 400)
            ->nonQueued()
            ->withResponsiveImages()
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
            ->withResponsiveImages()
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
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 470, 224)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_mission');

        // Illustrations organisation
        $this->addMediaConversion('large')
            ->fit(Manipulations::FIT_CROP, 1890, 900)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_organisation');
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 470, 224)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('domaine__illustrations_organisation');
    }

    public function banner()
    {
        // return $this->media()->where()
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'domaine__banner');
    }

    // public function getBannerAttribute()
    // {
    //     $media = $this->getFirstMedia('domaine__banner');
    //     return $media ? $media->getFormattedMediaField() : null;
    // }

    // public function getIllustrationsAttribute()
    // {
    //     return $this->getMedia('domaine__illustrations')->map(function ($media) {
    //         return $media->getFormattedMediaField();
    //     })->values();
    // }

    // public function getLogosPartenairesAttribute()
    // {
    //     return $this->getMedia('domaine__logos_partenaires')->map(function ($media) {
    //         return $media->getFormattedMediaField();
    //     })->values();
    // }

    // public function getLogosPartenairesActifsAttribute()
    // {
    //     return $this->getMedia('domaine__logos_partenaires_actifs')->map(function ($media) {
    //         return $media->getFormattedMediaField();
    //     })->values();
    // }

    // public function getIllustrationsMissionAttribute()
    // {
    //     return $this->getMedia('domaine__illustrations_mission')->map(function ($media) {
    //         return $media->getFormattedMediaField();
    //     })->values();
    // }

    // public function getIllustrationsOrganisationAttribute()
    // {
    //     return $this->getMedia('domaine__illustrations_organisation')->map(function ($media) {
    //         return $media->getFormattedMediaField();
    //     })->values();
    // }
}
