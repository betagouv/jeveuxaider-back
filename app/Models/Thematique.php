<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Thematique extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'thematiques';

    protected $fillable = [
        'name',
        'slug',
        'domaine_id',
        'published'
    ];

    protected $casts = [
        'published' => 'boolean',
    ];

    protected $appends = ['image'];

    protected $hidden = ['media'];

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
        return $this->belongsTo('Spatie\Tags\Tag');
    }
}
