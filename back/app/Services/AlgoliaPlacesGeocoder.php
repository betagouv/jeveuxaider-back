<?php

namespace App\Services;

use Algolia\AlgoliaSearch\PlacesClient;

class AlgoliaPlacesGeocoder
{

    public static function getCoordinatesForZip($zip)
    {
        if (!empty($zip)) {
            $places = PlacesClient::create(env('MIX_ALGOLIA_PLACES_APP_ID'), env('MIX_ALGOLIA_PLACES_API_KEY'));
            $result = $places->search(
                $zip,
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
                return [
                    'latitude' => $result['_geoloc']['lat'],
                    'longitude' => $result['_geoloc']['lng'],
                ];
            } else {
                return null;
            }
        }
    }
}
