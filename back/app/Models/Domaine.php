<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
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
            ->performOnCollections('domaines_banner');
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 470, 224)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('domaines_banner');

        // Illustrations
        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 400, 400)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('domaines_illustrations');
        $this->addMediaConversion('carousel')
            ->fit(Manipulations::FIT_CROP, 860, 860)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('domaines_illustrations');

        // Logos partenaires
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('domaines_logos_partenaires');
        $this->addMediaConversion('small')
            ->height(112)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('domaines_logos_partenaires');
    }

    public function getBannerAttribute()
    {
        $media = $this->getFirstMedia('domaines_banner');
        return $media ? $media->getFormattedMediaField() : null;
    }

    public function getIllustrationsAttribute()
    {
        return $this->getMedia('domaines_illustrations')->map(function ($media) {
            return $media->getFormattedMediaField();
        })->values();
    }

    public function getLogosPartenairesAttribute()
    {
        return $this->getMedia('domaines_logos_partenaires')->map(function ($media) {
            return $media->getFormattedMediaField();
        })->values();
    }

    // public function missionTemplates()
    // {
    //     return $this->hasMany('App\Models\MissionTemplate');
    // }
}
