<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Http\UploadedFile;

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
        'profile_id'
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
        return $media ? $media->getFullUrl() : null;
    }

    public function setImageAttribute($image)
    {
        if ($image == null) {
            $media = $this->getFirstMedia('collectivities');
            if ($media) {
                $media->delete();
            }
        } elseif (Str::startsWith($image, 'data:image')) {
            $this->addMediaFromBase64($image)->toMediaCollection('collectivities');
        } elseif ($image instanceof UploadedFile) {
            $this->addMedia($image)->toMediaCollection('collectivities');
        }
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
}
