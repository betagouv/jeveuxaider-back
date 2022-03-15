<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Media as ModelMedia;
use Illuminate\Support\Facades\Auth;
use Algolia\AlgoliaSearch\PlacesClient;
use App\Traits\HasMissingFields;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Image\Manipulations;
use Spatie\Activitylog\LogOptions;

class Territoire extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, HasSlug, HasMissingFields;

    protected $table = 'territoires';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'zips' => 'array',
        'tags' => 'array',
        'is_published' => 'boolean',
        'seo_engage_paragraphs' => 'json',
        'missing_fields' => 'array'
    ];

    protected $attributes = [
        'state' => 'validated'
    ];

    protected $checkFields = ['banner', 'suffix_title', 'department', 'zips' ,'tags' ,'seo_recruit_title', 'seo_recruit_description', 'seo_engage_title', 'seo_engage_paragraphs'];

    protected $appends = ['full_url'];
    // protected $appends = ['completion_rate', 'full_url', 'permissions'];

    //protected $hidden = ['media'];

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
        return Mission::ofTerritoire($this->id)->sum('places_left');
    }

    // public function getCompletionRateAttribute()
    // {
    //     $fields = [
    //         ['name' => 'banner', 'label' => 'Bannière'],
    //         ['name' => 'suffix_title', 'label' => 'Suffix du titre'],
    //         ['name' => 'department', 'label' => "Département"],
    //         ['name' => 'tags', 'label' => "Tags"],
    //         ['name' => 'seo_recruit_title', 'label' => "Titre pour le recrutement"],
    //         ['name' => 'seo_recruit_description', 'label' => "Description pour le recrutement"],
    //         ['name' => 'seo_engage_title', 'label' => "Titre pour l'engagement"],
    //         ['name' => 'seo_engage_paragraphs', 'label' => "Description pour l'engagement"],
    //     ];
    //     $existingFieldsCount = 0;
    //     $missingFields = [];

    //     if ($this->type == 'city') {
    //         $fields[] = ['name' => 'logo', 'label' => "Logo"];
    //     }

    //     foreach ($fields as $field) {
    //         if ($this->{$field['name']}) {
    //             $existingFieldsCount++;
    //         } else {
    //             $missingFields[] = $field;
    //         }
    //     }

    //     return [
    //         'score' => round($existingFieldsCount / count($fields) * 100),
    //         'missing_fields' => $missingFields
    //     ];
    // }

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
            // ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__banner');
        $this->addMediaConversion('tablet')
            ->width(1536)
            ->crop(Manipulations::CROP_CENTER, 1536, 960)
            ->nonQueued()
            ->withResponsiveImages()
            // ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__banner');
        $this->addMediaConversion('mobile')
            ->height(1144)
            ->crop(Manipulations::CROP_CENTER, 850, 1144)
            ->nonQueued()
            ->withResponsiveImages()
            // ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__banner');

        // Logo
        $this->addMediaConversion('formPreview')
            ->height(80)
            ->nonQueued()
            // ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__logo', 'territoire__promoted_organisations');
        $this->addMediaConversion('small')
            ->height(220)
            ->nonQueued()
            ->withResponsiveImages()
            // ->format(Manipulations::FORMAT_WEBP)
            ->performOnCollections('territoire__logo', 'territoire__promoted_organisations');
    }

    public function responsables()
    {
        return $this->belongsToMany('App\Models\Profile');
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitable');
    }

    public function structure()
    {
        return $this->belongsTo('App\Models\Structure');
    }

    // public function promotedOrganisations()
    // {
    //     return $this->morphedByMany('App\Models\Structure', 'relation', 'territoire_relations');
    // }

    public function addResponsable(Profile $profile)
    {
        return $this->responsables()->attach($profile);
    }

    public function deleteResponsable(Profile $profile)
    {
        $this->responsables()->detach($profile);

        $profile->user->resetContextRole();
        $profile->user->save();

        return $this->load('responsables');
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

    public function getPermissionsAttribute()
    {
        return[
            'canViewStats' => Auth::guard('api')->user() ? Auth::guard('api')->user()->can('viewStats', $this) : false
        ];
    }

    public function promotedMissions($limit = 7)
    {
        $territoire = $this;
        $missions = Mission::search('', function ($algolia, $query, $options) use ($territoire, $limit) {
            $config =  [
                'filters' => 'provider:reserve_civique',
                'aroundPrecision' => 2000,
                'hitsPerPage' => $limit,
            ];

            if ($territoire->type == 'department') {
                $departmentName = config('taxonomies.departments')["terms"][$territoire->department];
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
