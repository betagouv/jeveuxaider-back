<?php

namespace App\Http\Controllers\API;

use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class EngagementController extends Controller
{
    public function import()
    {
        $response = Http::withHeaders([
            'apikey' => config('app.api_engagement_key'),
        ])->get('http://api.jobsmail.tech/v0/mission?limit=1000');

        if (!$response->successful()) {
            return false;
        }

        // Format for Algolia
        $missions = array_map(fn ($mission) => [
            'objectID' => 'ApiEngagement/' . $mission['_id'],
            'publisher_name' => $mission['publisherName'],
            'application_url' => $mission['applicationUrl'],
            'id' => $mission['_id'],
            'name' => $mission['title'],
            'city' => $mission['city'] ?? null,
            'department' => $mission['department'] ?? null,
            'department_name' => $mission['department'] ?? null,
            'zip' => $mission['postalCode'] ?? null,
            'places_left' => $mission['places'],
            'participations_max' => $mission['places'],
            'has_places_left' => true,
            'structure' => [
                'id' => $mission['organizationId'] ?? null,
                'name' => $mission['organizationName'] ?? null,
            ],
            // 'type' => $this->type, ( Mission en présentiel / Mission à distance )
            'template_title' => 'Titre du template',
            'domaine_name' => 'Protection de la nature',
            'domaine_image' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/154/FFR5Cx5qbSjCBy0.svg', // Url de l'icone du domaine
            'domaines' => ['Protection de la nature'],
            'provider' => 'api_engagement'
        ], $response['data']);

        // Send to Algolia
        $client = SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
        );

        $index = $client
            ->initIndex(config('scout.prefix').'_covid_missions')
            ->saveObjects($missions);

        return $missions;
        // return $response->json();
    }

    public function delete()
    {
        $client = SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
        );

        $res = $client
            ->initIndex(config('scout.prefix').'_covid_missions')
            ->deleteBy([
            'facetFilters' => 'provider:api_engagement',
        ]);

        return $res->getBody();
    }
}
