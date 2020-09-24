<?php

namespace App\Http\Controllers\API;

use Algolia\AlgoliaSearch\SearchClient;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Builder;

class EngagementController extends Controller
{
    public function feed()
    {
        $missions = Mission::whereHas('structure', function (Builder $query) {
            $query->where('state', 'Validée');
        })->where('state', 'Validée')->get();

        return response()->view('flux-api-engagement', compact('missions'))->header('Content-Type', 'text/xml');
    }

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
            'department' => $mission['departmentCode'] ?? null,
            'department_name' => isset($mission['departmentName']) && !empty($mission['departmentName']) ? $mission['departmentCode'] . ' - ' . $mission['departmentName'] : null,
            'zip' => $mission['postalCode'] ?? null,
            'places_left' => $mission['places'] ?? null,
            'participations_max' => $mission['places'] ?? null,
            'has_places_left' => true,
            'structure' => [
                'id' => $mission['organizationId'] ?? null,
                'name' => $mission['organizationName'] ?? null,
            ],
            // 'type' => $this->type, ( Mission en présentiel / Mission à distance )
            'template_title' => $this->getTemplate($mission),
            'domaine_name' => 'Protection de la nature',
            'domaine_image' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/154/FFR5Cx5qbSjCBy0.svg', // Url de l'icone du domaine
            'domaines' => ['Protection de la nature'],
            'provider' => 'api_engagement',
            '_geoloc' => [
                'lat' => isset($mission['location']) && isset($mission['location']['lat']) ? $mission['location']['lat'] : 0,
                'lng' => isset($mission['location']) && isset($mission['location']['lon']) ? $mission['location']['lon'] : 0
            ],
            'post_date' => strtotime($mission['postedAt']),
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
        // return $response->json(); //
    }

    private function getTemplate($mission)
    {
        if (strpos($mission['title'], "J'alerte en cas d'urgence") !== false) {
            return "J'alerte en cas d'urgence";
        }
        if (strpos($mission['title'], "J'observe la faune et la flore") !== false) {
            return "J'observe la faune et la flore";
        }
        if (strpos($mission['title'], "J'aménage un espace naturel") !== false) {
            return "J'aménage un espace naturel";
        }
        if (strpos($mission['title'], "Je ramasse des déchets") !== false) {
            return "Je ramasse des déchets";
        }
        if (strpos($mission['title'], "Je protège la faune et la flore") !== false) {
            return "Je protège la faune et la flore";
        }
        if (strpos($mission['title'], "Je découvre la biodiversité") !== false) {
            return "Je découvre la biodiversité";
        }
        return $mission['title'];
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
