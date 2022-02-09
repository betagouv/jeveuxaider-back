<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

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
        $this->addMediaConversion('large')
            ->width(2000)
            ->height(750)
            ->nonQueued()
            ->performOnCollections('thematiques');

        $this->addMediaConversion('thumb')
            ->width(600)
            ->height(225)
            ->nonQueued()
            ->performOnCollections('thematiques');
    }

    // public function missionTemplates()
    // {
    //     return $this->hasMany('App\Models\MissionTemplate');
    // }

}
