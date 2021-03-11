<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\Auth;
use Algolia\AlgoliaSearch\PlacesClient;

class Collectivity extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity;

    protected $table = 'collectivities';

    protected $fillable = [
        'name',
        'title',
        'description',
        'slug',
        'zips',
        'type',
        'department',
        'published',
        'state',
        'profile_id',
        'structure_id',
    ];

    protected $casts = [
        'zips' => 'array',
        'published' => 'boolean',
    ];

    protected $attributes = [
        'state' => 'validated'
    ];

    // protected $appends = ['banner', 'logo', 'image_1', 'image_2', 'image_3', 'image_4', 'image_5', 'image_6'];

    protected $hidden = ['media'];

    protected static $logFillable = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function getBannerAttribute()
    {
        return $this->getMediaUrls('banner');
    }

    public function getLogoAttribute()
    {
        return $this->getMediaUrls('logo');
    }

    public function getImage1Attribute()
    {
        return $this->getMediaUrls('image_1');
    }

    public function getImage2Attribute()
    {
        return $this->getMediaUrls('image_2');
    }

    public function getImage3Attribute()
    {
        return $this->getMediaUrls('image_3');
    }

    public function getImage4Attribute()
    {
        return $this->getMediaUrls('image_4');
    }

    public function getImage5Attribute()
    {
        return $this->getMediaUrls('image_5');
    }

    public function getImage6Attribute()
    {
        return $this->getMediaUrls('image_6');
    }

    protected function getMediaUrls($field)
    {
        $media = $this->getFirstMedia('collectivities', ['field' => $field]);
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
            ->performOnCollections('collectivities');

        $this->addMediaConversion('thumb')
            ->width(600)
            ->height(225)
            ->nonQueued()
            ->performOnCollections('collectivities');
    }

    public function profiles()
    {
        return $this->hasMany('App\Models\Profile');
    }

    public function scopeRole($query, $contextRole)
    {
        switch ($contextRole) {
            case 'admin':
            case 'analyste':
                return $query;
            break;
            case 'referent':
                $department = Auth::guard('api')->user()->profile->referent_department;
                $zips = self::where('type', 'commune')
                    ->get()
                    ->pluck('zips')
                    ->flatten()
                    ->filter(function ($item) use ($department) {
                        return substr($item, 0, 2) == $department;
                    })
                    ->toArray();

                if ($zips) {
                    foreach ($zips as $zip) {
                        $query->orWhereJsonContains('zips', $zip);
                    }
                    return $query;
                }
                    return $query->where('id', -1); // Hack pour ne rien retourner

            break;
            case 'referent_regional':

                $departments = config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region];;
                $zips = self::where('type', 'commune')
                    ->get()
                    ->pluck('zips')
                    ->flatten()
                    ->filter(function ($item) use ($departments) {
                        return in_array(substr($item, 0, 2), $departments);
                    })
                    ->toArray();

                if ($zips) {
                    foreach ($zips as $zip) {
                        $query->orWhereJsonContains('zips', $zip);
                    }
                    return $query;
                }
                    return $query->where('id', -1); // Hack pour ne rien retourner

            break;
            case 'responsable_collectivity':
                return $query->where('id', Auth::guard('api')->user()->profile->collectivity->id);
            break;
        }
    }

    public function setCoordonates()
    {
        if (!empty($this->zips)) {
            $places = PlacesClient::create(env('MIX_ALGOLIA_PLACES_APP_ID'), env('MIX_ALGOLIA_PLACES_API_KEY'));
            $result = $places->search(
                $this->zips[0],
                [
                    'restrictSearchableAttributes' => 'postcode',
                    'type' => 'city',
                    'hitsPerPage' => 1,
                    'countries' => 'fr',
                    'language' => 'fr'
                ]
            );

            if (!empty($result['nbHits'])) {
                $result = $result['hits'][0];
                $this->latitude = $result['_geoloc']['lat'];
                $this->longitude = $result['_geoloc']['lng'];
            }
        }
    }
}
