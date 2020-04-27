<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Collectivity extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'collectivities';

    protected $fillable = [
        'title',
        'slug',
        'zips',
        'type',
        'department',
        'description',
        'state',
        'profile_id',
    ];

    protected $casts = [
        'zips' => 'array'
    ];

    protected $attributes = [
        'state' => 'validated'
    ];

    protected $appends = ['image'];

    protected $hidden = ['media'];

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('collectivities');
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->width(1280)
            ->height(680)
            ->performOnCollections('collectivities');

        $this->addMediaConversion('thumb')
            ->width(384)
            ->height(255)
            ->performOnCollections('collectivities');
    }
}
