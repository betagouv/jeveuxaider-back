<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Thematique extends Model implements HasMedia
{
    use InteractsWithMedia, HasSlug;

    protected $table = 'thematiques';

    protected $fillable = [
        'name',
        'title',
        'domaine_id',
        'published',
        'description',
        'color'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $appends = ['image', 'full_url'];

    protected $hidden = ['media'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($thematique) {
                return $thematique->name;
            })
            ->saveSlugsTo('slug');
    }

    public function getFullUrlAttribute()
    {
        return "/domaines-action/$this->slug";
    }

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('thematiques');
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
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

    public function missionTemplates()
    {
        return $this->hasMany('App\Models\MissionTemplate');
    }

    public function domaine()
    {
        return $this->belongsTo('App\Models\Tag');
    }
}
