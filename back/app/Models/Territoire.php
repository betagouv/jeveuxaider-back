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
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Carbon\Carbon;

class Territoire extends Model implements HasMedia
{
    use InteractsWithMedia, LogsActivity, HasSlug;

    protected $table = 'territoires';

    protected $guarded = [
        'id'
    ];

    protected $casts = [
        'zips' => 'array',
        'tags' => 'array',
        'is_published' => 'boolean',
        'seo_engage_paragraphs' => 'json',
    ];

    protected $attributes = [
        'state' => 'validated'
    ];

    // protected $appends = ['completion_rate', 'full_url', 'banner', 'logo', 'permissions'];

    protected $hidden = ['media'];

    protected static $logUnguarded = true;

    protected static $logOnlyDirty = true;

    protected static $submitEmptyLogs = false;


    public function getBannerAttribute()
    {
        return $this->getMediaUrls('banner');
    }

    public function getLogoAttribute()
    {
        return $this->getMediaUrls('logo');
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

    public function getCompletionRateAttribute()
    {
        $fields = [
            ['name' => 'banner', 'label' => 'Bannière'],
            ['name' => 'suffix_title', 'label' => 'Suffix du titre'],
            ['name' => 'department', 'label' => "Département"],
            ['name' => 'tags', 'label' => "Tags"],
            ['name' => 'seo_recruit_title', 'label' => "Titre pour le recrutement"],
            ['name' => 'seo_recruit_description', 'label' => "Description pour le recrutement"],
            ['name' => 'seo_engage_title', 'label' => "Titre pour l'engagement"],
            ['name' => 'seo_engage_paragraphs', 'label' => "Description pour l'engagement"],
        ];
        $existingFieldsCount = 0;
        $missingFields = [];

        if ($this->type == 'city') {
            $fields[] = ['name' => 'logo', 'label' => "Logo"];
        }

        foreach ($fields as $field) {
            if ($this->{$field['name']}) {
                $existingFieldsCount++;
            } else {
                $missingFields[] = $field;
            }
        }

        return [
            'score' => round($existingFieldsCount / count($fields) * 100),
            'missing_fields' => $missingFields
        ];
    }

    protected function getMediaUrls($field)
    {
        $media = $this->getFirstMedia('territoires', ['field' => $field]);
        if ($media) {
            $mediaUrls = ['original' => $media->getFullUrl()];
            foreach ($media->getGeneratedConversions() as $key => $conversion) {
                $mediaUrls[$key] = $media->getUrl($key);
            }
            return $mediaUrls;
        }
        return null;
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name'])
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('large')
            ->width(2000)
            ->nonQueued()
            ->performOnCollections('territoires');

        $this->addMediaConversion('thumb')
            ->width(600)
            ->height(225)
            ->nonQueued()
            ->performOnCollections('territoires');
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

    public function structures()
    {
        return $this->morphedByMany('App\Models\Structure', 'relation', 'territoire_relations');
    }

    public function addResponsable(Profile $profile)
    {
        return $this->responsables()->attach($profile);
    }

    public function deleteResponsable(Profile $profile)
    {
        $this->responsables()->detach($profile);

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

    public function promotedMissions($byRadius = false, $limit = 4)
    {
        // Missions within the territory postcodes, random sort
        if (!$byRadius) {
            $missions = Mission::ofTerritoire($this->id)
                ->where('state', 'Validée')
                ->where('places_left', '>', 0)
                ->where('type', 'Mission en présentiel')
                ->where(function ($query) {
                    $query
                        ->where('end_date', '>', Carbon::now())
                        ->orWhereNull('end_date');
                })
                ->with('structure')
                ->inRandomOrder()
                ->limit($limit)
                -> get();
        } else {
            // By radius, sorted by score and distance (like the search page)
            $territoire = $this;
            $missions = Mission::search('', function ($algolia, $query, $options) use ($territoire, $limit) {
                $config =  [
                    'filters' => 'has_places_left=1 AND provider:reserve_civique',
                    'aroundPrecision' => 2000,
                    'hitsPerPage' => $limit,
                ];

                if ($territoire->type == 'department') {
                    $departmentName = config('taxonomies.departments')["terms"][$territoire->department];
                    $config = array_merge($config, [
                        'facetFilters' => [
                            'department_name:' . $territoire->department . ' - ' . $departmentName,
                        ],
                    ]);
                } else {
                    $config = array_merge($config, [
                        'aroundLatLng' => $territoire->latitude . ',' . $territoire->longitude,
                        'aroundRadius' => 35000,
                        'facetFilters' => ['type:Mission en présentiel'],
                    ]);
                }

                $options = array_merge($options, $config);
                return $algolia->search($query, $options);
            });

            $missions = $missions->get()->load(['structure'])->append('score');
        }


        return $missions;
    }
}
