<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Media as ModelMedia;
use Spatie\Image\Manipulations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;

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

    public function registerMediaConversions(Media $media = null): void
    {
        // 2x for high pixel density

        $this->addMediaConversion('card')
            ->fit(Manipulations::FIT_CROP, 600, 286)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('mission_template__photo');

        $this->addMediaConversion('formPreview')
            ->fit(Manipulations::FIT_CROP, 470, 224)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('mission_template__photo');
    }

    public function photo()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'mission_template__photo');
    }

    public function domaine()
    {
        return $this->belongsTo('App\Models\Domaine');
    }

    public function activity()
    {
        return $this->belongsTo('App\Models\Activity');
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

    public function scopeAvailable($query)
    {
        $query->where('published', true)->where('state', 'validated');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
                return $query;
                break;
            case 'tete_de_reseau':
                return $query->ofReseau(Auth::guard('api')->user()->profile->tete_de_reseau_id);
                break;
            case 'responsable':
                return $query->available();
                break;
            default:
                abort(403, 'This action is not authorized');
                break;
        }
    }

    protected function description(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value, '<p><b><strong><ul><ol><li><i>'),
        );
    }

    protected function objectif(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => strip_tags($value, '<p><b><strong><ul><ol><li><i>'),
        );
    }

    public function getPlacesLeftAttribute()
    {
        return Mission::available()->ofTemplate($this->id)->sum('places_left');
    }
}
