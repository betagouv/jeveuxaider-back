<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag implements HasMedia
{
    use InteractsWithMedia, Searchable;

   // protected $appends = ['image'];

   // protected $hidden = ['media'];

    public function shouldBeSearchable()
    {
        return $this->type == 'competence';
    }

    public function searchableAs()
    {
        return config('scout.prefix').'_covid_competences';
    }

    // public function getImageAttribute()
    // {
    //     $media = $this->getFirstMedia('tags');
    //     if ($media) {
    //         return $media->getFullUrl();
    //     }
    //     return null;
    // }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class, 'taggable');
    }
}
