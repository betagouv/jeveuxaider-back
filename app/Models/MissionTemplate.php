<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
        'domaine_id'
    ];

    protected $attributes = [
        'priority' => false,
        'published' => true
    ];

    protected $casts = [
        'priority' => 'boolean',
        'published' => 'boolean',
    ];

    protected $appends = ['image'];

    protected $hidden = ['media'];

    public function getImageAttribute()
    {
        $media = $this->getFirstMedia('templates');
        
        if ($media) {
            return $media->getFullUrl();
        }

        return $this->domaine->image;
    }

    public function domaine()
    {
        return $this->belongsTo('App\Models\Tag');
    }

    public function missions()
    {
        return $this->hasMany('App\Models\Mission', 'template_id');
    }
}
