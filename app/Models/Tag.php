<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Tags\Tag as SpatieTag;

class Tag extends SpatieTag implements HasMedia
{
    use InteractsWithMedia;

    protected $appends = ['image'];

    protected $hidden = ['media'];

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('tags');
        if ($media) {
            return $media->getFullUrl();
        }
        return null;
    }
}
