<?php

namespace App\Http\Controllers\Api;

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
        })->where('state', 'Validée')->where('places_left', '>', 0)->get();

        return response()->view('flux-api-engagement', compact('missions'))->header('Content-Type', 'text/xml');
    }

    public function import()
    {
        $limit = 1000;
        $done = 0;
        $client = SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
        );

        do {
            $response = Http::withHeaders([
                'apikey' => config('app.api_engagement_key'),
            ])->get("http://api.jobsmail.tech/v0/mission?limit=$limit&skip=$done&domain=environnement,solidarite-insertion,sante,culture-loisirs,education,sport");

            if (!$response->successful()) {
                return false;
            }

            // Send to Algolia
            $missions = array_map(fn ($mission) =>
                $this->formatMission($mission), $response['data']);
            $client->initIndex(config('scout.prefix').'_covid_missions')->saveObjects($missions);

            $total = $response['total'];
            $done += $limit;
        } while ($done < $total);

        return $missions;
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

    private function formatTitle($mission)
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

    private function formatDomain($mission)
    {
        switch ($mission['domain']) {
            case 'environnement':
                return ['name' => 'Protection de la nature', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/154/FFR5Cx5qbSjCBy0.svg'];
                break;
            case 'solidarite-insertion':
                return ['name' => 'Solidarité et insertion', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/1047/IMzA4pHnRjHGMeM.svg'];
                break;
            case 'sante':
                return ['name' => 'Santé pour tous', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/153/LXzqaXwNUXoycsj.svg'];
                break;
            case 'culture-loisirs':
                return ['name' => 'Art et culture pour tous', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/161/ZLbnYJvmSJTl6FH.svg'];
                break;
            case 'education':
                return ['name' => 'Éducation pour tous', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/152/IIgYOvFa9Lx5zDz.svg'];
                break;
            case 'emploi':
                return ['name' => 'Emploi', 'logo' => ''];
                break;
            case 'sport':
                return ['name' => 'Sport pour tous', 'logo' => 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/157/ai45u4kEBbOJ820.svg'];
                break;
            case 'humanitaire':
                return ['name' => 'Humanitaire', 'logo' => ''];
                break;
            case 'animaux':
                return ['name' => 'Animaux', 'logo' => ''];
                break;
            case 'vivre-ensemble':
                return ['name' => 'Vivre ensemble', 'logo' => ''];
                break;
            case 'autre':
                return ['name' => 'Autre', 'logo' => ''];
                break;
            default:
                return ['name' => '', 'logo' => ''];
                break;
        }
    }

    private function formatRemote($mission)
    {
        if (!isset($mission['remote'])) {
            return null;
        }

        switch ($mission['remote']) {
            case 'no':
                return 'Mission en présentiel ';
                break;
            case 'possible':
                return 'Mission en présentiel ';
                break;
            case 'full':
                return 'Mission à distance';
                break;
            default:
                return null;
                break;
        }
    }

    private function formatMission($mission)
    {
        return [
            'objectID' => 'ApiEngagement/' . $mission['_id'],
            'publisher_name' => $mission['publisherName'],
            'publisher_logo' => $mission['publisherLogo'],
            'application_url' => $mission['applicationUrl'],
            'id' => $mission['_id'],
            'name' => $mission['title'],
            'city' => $mission['city'] ?? null,
            'department' => $mission['departmentCode'] ?? null,
            'department_name' => isset($mission['departmentName']) && !empty($mission['departmentName']) ? $mission['departmentCode'] . ' - ' . $mission['departmentName'] : null,
            'zip' => $mission['postalCode'] ?? null,
            'places_left' => $mission['places'] ?? null,
            'participations_max' => $mission['places'] ?? null,
            'has_places_left' => isset($mission['places']) ? ($mission['places'] > 0 ? true : false) : null,
            'structure' => [
                'id' => $mission['organizationId'] ?? null,
                'name' => $mission['organizationName'] ?? null,
            ],
            'type' => $this->formatRemote($mission),
            'template_title' => $this->formatTitle($mission),
            'domaine_name' => $this->formatDomain($mission)['name'],
            'domaine_image' => $this->formatDomain($mission)['logo'], // Url de l'icone du domaine
            'domaines' => [$this->formatDomain($mission)['name']],
            'provider' => 'api_engagement',
            '_geoloc' => [
                'lat' => isset($mission['location']) && isset($mission['location']['lat']) ? $mission['location']['lat'] : 0,
                'lng' => isset($mission['location']) && isset($mission['location']['lon']) ? $mission['location']['lon'] : 0
            ],
            'post_date' => strtotime($mission['postedAt']),
            'all' => $mission,
        ];
    }
}
