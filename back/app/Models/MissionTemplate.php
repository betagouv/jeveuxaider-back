<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Image\Manipulations;

class MissionTemplate extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'mission_templates';

    protected $guarded = [
        'id',
    ];

    protected $attributes = [
        'priority' => false,
        'published' => false
    ];

    protected $casts = [
        'priority' => 'boolean',
        'published' => 'boolean',
    ];

    // protected $appends = ['photo'];

    public function registerMediaConversions(Media $media = null): void
    {
        // 2x for high pixel density

        // $this->addMediaConversion('large')
        //     ->width(640)
        //     ->nonQueued()
        //     ->performOnCollections('templates');

        // $this->addMediaConversion('thumb')
        //     ->width(320)
        //     ->nonQueued()
        //     ->performOnCollections('templates');

        // $this->addMediaConversion('xxl')
        //     ->width(1440)
        //     ->nonQueued()
        //     ->performOnCollections('templates');

        $this->addMediaConversion('card')
            ->fit(Manipulations::FIT_CROP, 600, 600)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('templates');

        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 200, 200)
            ->nonQueued()
            ->withResponsiveImages()
            ->performOnCollections('templates');
    }

    public function getPhotoAttribute()
    {
        $media = $this->getFirstMedia('templates', ['attribute' => 'photo']);
        return $media ? $media->getFormattedMediaField() : null;
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
