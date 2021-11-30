<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag
{
    use InteractsWithMedia, Searchable;

    public function shouldBeSearchable()
    {
        return $this->type == 'competence';
    }

    public function searchableAs()
    {
        return config('scout.prefix').'_covid_competences';
    }

    public function profiles()
    {
        return $this->morphedByMany(Profile::class, 'taggable');
    }
}
