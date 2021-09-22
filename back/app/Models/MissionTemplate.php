<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MissionTemplate extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'mission_templates';

    protected $fillable = [
        'title',
        'subtitle',
        'objectif',
        'description',
        'priority',
        'published',
        'reseau_id',
        'domaine_id',
    ];

    protected $attributes = [
        'priority' => false,
        'published' => true
    ];

    protected $casts = [
        'priority' => 'boolean',
        'published' => 'boolean',
    ];

    protected $appends = ['image', 'photo'];

    protected $hidden = ['media'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->width(640)
            ->nonQueued()
            ->performOnCollections('templates');

        $this->addMediaConversion('thumb')
            ->width(320)
            ->nonQueued()
            ->performOnCollections('templates');

        $this->addMediaConversion('xxl')
            ->width(1440)
            ->nonQueued()
            ->performOnCollections('templates');
    }

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('templates');

        if ($media) {
            return $media->getFullUrl();
        }

        return $this->domaine->image;
    }

    public function getPhotoAttribute()
    {
        return $this->getMediaUrls('photo');
    }

    protected function getMediaUrls($field)
    {
        $media = $this->getFirstMedia('templates', ['field' => $field]);
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function domaine()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission', 'template_id');
    }

    public function reseau()
    {
        return $this->belongsTo(Reseau::class);
    }

    public function scopeOfReseau($query, $reseau_id)
    {
        $query->where('reseau_id', $reseau_id);
    }
}
