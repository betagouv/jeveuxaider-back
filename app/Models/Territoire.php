<?php

namespace App\Models;

use App\Models\Media as ModelMedia;
use App\Services\ApiAdresse;
use App\Traits\HasMissingFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Territoire extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, HasSlug, HasMissingFields;

    protected $table = 'territoires';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'zips' => 'array',
        'tags' => 'array',
        'is_published' => 'boolean',
        'seo_engage_paragraphs' => 'json',
        'missing_fields' => 'array',
    ];

    protected $attributes = [
        'state' => 'validated',
    ];

    protected $checkFields = ['banner', 'suffix_title', 'department', 'zips', 'seo_recruit_title', 'seo_recruit_description', 'seo_engage_title', 'seo_engage_paragraphs'];

    protected $appends = ['full_url'];

    public function banner()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'territoire__banner');
    }

    public function logo()
    {
        return $this->morphOne(ModelMedia::class, 'model')->where('collection_name', 'territoire__logo');
    }

    public function promotedOrganisations()
    {
        return $this->morphMany(ModelMedia::class, 'model')->where('collection_name', 'territoire__promoted_organisations');
    }

    public function getFullUrlAttribute()
    {
        switch ($this->type) {
            case 'department':
                return "/departements/$this->slug";
                break;

            case 'city':
                return "/villes/$this->slug";
                break;
        }
    }

    public function getPlacesLeftAttribute()
    {
        return Mission::available()->ofTerritoire($this->id)->sum('places_left');
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
            ->performOnCollections('territoire__banner');
        $this->addMediaConversion('tablet')
            ->width(1536)
            ->crop(Manipulations::CROP_CENTER, 1536, 960)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__banner');
        $this->addMediaConversion('mobile')
            ->height(1144)
            ->crop(Manipulations::CROP_CENTER, 850, 1144)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__banner');

        // Logo
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__logo', 'territoire__promoted_organisations');
        $this->addMediaConversion('small')
            ->height(220)
            ->nonQueued()
            ->withResponsiveImages()
            ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__logo', 'territoire__promoted_organisations');
    }

    public function responsables()
    {
        return $this->morphToMany(User::class, 'rolable', 'rolables');
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    public function addResponsable(User $user, $fonction = null, $invitedByUserId = null)
    {
        return $user->assignRole('responsable_territoire', $this, $fonction, $invitedByUserId);
    }

    public function deleteResponsable(User $user)
    {
        $this->responsables()->detach($user);

        $user->resetContextRole();
        $user->save();

        return $this->load('responsables');
    }

    public function setCoordonates()
    {
        if (!empty($this->zips)) {
            $place = ApiAdresse::search(['q' => $this->zips[0], 'type' => 'municipality', 'limit' => 1]);
            if (!empty($place)) {
                $this->latitude = $place['geometry']['coordinates'][1];
                $this->longitude = $place['geometry']['coordinates'][0];
            }
        }
    }

    // public function getPermissionsAttribute()
    // {
    //     return [
    //         'canViewStats' => Auth::guard('api')->user() ? Auth::guard('api')->user()->can('viewStats', $this) : false,
    //     ];
    // }

    public function promotedMissions($limit = 7)
    {
        $territoire = $this;
        $missions = Mission::search('', function ($algolia, $query, $options) use ($territoire, $limit) {
            $config = [
                'filters' => 'provider:reserve_civique AND is_autonomy=0',
                'aroundPrecision' => 2000,
                'hitsPerPage' => $limit,
            ];

            if ($territoire->type == 'department') {
                $departmentName = config('taxonomies.departments')['terms'][$territoire->department];
                $config = array_merge($config, [
                    'facetFilters' => [
                        'department_name:' . $territoire->department . ' - ' . $departmentName,
                    ],
                    'aroundLatLngViaIP' => true,
                ]);
            } else {
                if ($territoire->latitude && $territoire->longitude) {
                    $config = array_merge($config, [
                        'aroundLatLng' => $territoire->latitude . ',' . $territoire->longitude,
                        'aroundRadius' => 35000,
                        'facetFilters' => ['type:Mission en présentiel'],
                    ]);
                } else {
                    $config = array_merge($config, [
                        'facetFilters' => ['type:Mission en présentiel'],
                    ]);
                }
            }

            $options = array_merge($options, $config);

            return $algolia->search($query, $options);
        });

        $missions = $missions->get()->load(['structure'])->append('score');

        return $missions;
    }
}
