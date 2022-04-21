<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Media as ModelMedia;
use App\Traits\HasMissingFields;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Image\Manipulations;
use Spatie\Activitylog\LogOptions;

class Activity extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, HasSlug, HasMissingFields;

    protected $table = 'activities';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'seo_engage_paragraphs' => 'json',
    ];

    protected $checkFields = ['banner', 'seo_recruit_title', 'seo_recruit_description', 'seo_engage_title', 'seo_engage_paragraphs'];

    protected $appends = ['full_url'];

    public function domaines()
    {
        return $this->morphToMany(Domaine::class, 'domainable')->wherePivot('field', 'activity_domaines');
    }

    public function banner()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'activity__banner');
    }

    public function promotedOrganisations()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'activity__promoted_organisations');
    }

    public function getPlacesLeftAttribute()
    {
        return Mission::available()->ofActivity($this->id)->sum('places_left');
    }

    public function getFullUrlAttribute()
    {
        return "/activites/$this->slug";
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        // 2x for high pixel density

        // Banner
        $this->addMediaConversion('desktop')
            ->width(2850)
            ->crop(Manipulations::CROP_CENTER, 2850, 900)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('activity__banner');
        $this->addMediaConversion('tablet')
            ->width(1536)
            ->crop(Manipulations::CROP_CENTER, 1536, 960)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('activity__banner');
        $this->addMediaConversion('mobile')
            ->height(1144)
            ->crop(Manipulations::CROP_CENTER, 850, 1144)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('activity__banner');

        // Organisations
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('activity__promoted_organisations');
        $this->addMediaConversion('small')
            ->height(220)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('activity__promoted_organisations');
    }
}
