<?php

namespace App\Services;

use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Support\Facades\Http;

class ApiEngagement
{
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
            ])->get("https://api.api-engagement.beta.gouv.fr/v0/mission?limit=$limit&skip=$done&domain=environnement,solidarite-insertion,sante,culture-loisirs,education,sport,emploi,humanitaire,animaux,vivre-ensemble");


            // Send to Algolia
            $missions = array_map(
                fn ($mission) =>
                $this->formatMission($mission),
                $response['data']
            );
            $client->initIndex(config('scout.prefix') . '_covid_missions')->saveObjects($missions);

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
        ->initIndex(config('scout.prefix') . '_covid_missions')
        ->deleteBy([
        'facetFilters' => 'provider:api_engagement',
        ]);

        return $res->getBody();
    }

    private function formatTemplateTitle($mission)
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
        return null;
    }

    private function formatDomain($mission)
    {
        $baseUrl = 'https://reserve-civique-prod.osu.eu-west-2.outscale.com/public/production/';
        switch ($mission['domain']) {
            case 'environnement':
                return [
                    'id' => 4,
                    'name' => 'Protection de la nature',
                    'logo' => $baseUrl . '154/FFR5Cx5qbSjCBy0.svg'
                ];
            break;

            case 'solidarite-insertion':
                return [
                    'id' => 6,
                    'name' => 'Solidarité et insertion',
                    'logo' => $baseUrl . '1047/IMzA4pHnRjHGMeM.svg'
                ];
            break;

            case 'sante':
                return [
                    'id' => 3,
                    'name' => 'Santé pour tous',
                    'logo' => $baseUrl . '153/LXzqaXwNUXoycsj.svg'
                ];
            break;

            case 'culture-loisirs':
                return [
                    'id' => 11,
                    'name' => 'Art et culture pour tous',
                    'logo' => $baseUrl . '161/ZLbnYJvmSJTl6FH.svg'
                ];
            break;

            case 'education':
                return [
                    'id' => 2,
                    'name' => 'Éducation pour tous',
                    'logo' => $baseUrl . '152/IIgYOvFa9Lx5zDz.svg'
                ];
            break;

            case 'emploi':
                return [
                    'id' => 6,
                    'name' => 'Solidarité et insertion',
                    'logo' => $baseUrl . '1047/IMzA4pHnRjHGMeM.svg'
                ];
            break;

            case 'sport':
                return [
                    'id' => 7,
                    'name' => 'Sport pour tous',
                    'logo' => $baseUrl . '157/ai45u4kEBbOJ820.svg'
                ];
            break;

            case 'humanitaire':
                return [
                    'id' => 6,
                    'name' => 'Solidarité et insertion',
                    'logo' => $baseUrl . '1047/IMzA4pHnRjHGMeM.svg'
                ];
            break;

            case 'animaux':
                return [
                    'id' => 4,
                    'name' => 'Protection de la nature',
                    'logo' => $baseUrl . '154/FFR5Cx5qbSjCBy0.svg'
                ];
            break;

            case 'vivre-ensemble':
                return [
                    'id' => 6,
                    'name' => 'Solidarité et insertion',
                    'logo' => $baseUrl . '1047/IMzA4pHnRjHGMeM.svg'
                ];
            break;

            case 'autre':
                return [
                    'id' => 9,
                    'name' => 'Autre',
                    'logo' => ''
                ];
            break;

            default:
                return [
                    'id' => 9,
                    'name' => '',
                    'logo' => ''
                ];
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
                return 'Mission en présentiel';
            break;
            case 'possible':
                return 'Mission en présentiel';
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
        'department_name' => isset($mission['departmentName']) && !empty($mission['departmentName']) ?
            $mission['departmentCode'] . ' - ' . $mission['departmentName'] : null,
        'zip' => $mission['postalCode'] ?? null,
        'places_left' => $mission['places'] ?? null,
        'participations_max' => $mission['places'] ?? null,
        'has_places_left' => isset($mission['places']) ? ($mission['places'] > 0 ? true : false) : null,
        'structure' => [
        'id' => $mission['organizationId'] ?? null,
        'name' => $mission['organizationName'] ?? null,
        ],
        'type' => $this->formatRemote($mission),
        'template_title' => $this->formatTemplateTitle($mission),
        'domaine_name' => $this->formatDomain($mission)['name'],
        'domaine_image' => $this->formatDomain($mission)['logo'], // Url de l'icone du domaine
        'domaine_id' => $this->formatDomain($mission)['id'],
        'domaines' => [$this->formatDomain($mission)['name']],
        'provider' => 'api_engagement',
        '_geoloc' => [
        'lat' => isset($mission['location']) && isset($mission['location']['lat']) ? $mission['location']['lat'] : 0,
        'lng' => isset($mission['location']) && isset($mission['location']['lon']) ? $mission['location']['lon'] : 0
        ],
        'post_date' => strtotime($mission['postedAt']),
        ];
    }

    public static function prepareStructureAttributes($structureApi)
    {
        $attributes = [];
        $attributes['rna'] = isset($structureApi['rna']) ? $structureApi['rna'] : null;
        $attributes['statut_juridique'] = isset($structureApi['regime']) ? $structureApi['regime'] : null;
        $attributes['description'] = isset($structureApi['objet']) ? $structureApi['objet'] : null;
        $attributes['city'] = isset($structureApi['coordonnees']['adresse_siege']['commune']) ? $structureApi['coordonnees']['adresse_siege']['commune'] : null;
        $attributes['address'] = isset($structureApi['coordonnees']['adresse_siege']['voie'])
            ? implode(' ', [
                isset($structureApi['coordonnees']['adresse_siege']['num_voie']) ? $structureApi['coordonnees']['adresse_siege']['num_voie'] : '',
                isset($structureApi['coordonnees']['adresse_siege']['type_voie']) ? $structureApi['coordonnees']['adresse_siege']['type_voie'] : '',
                isset($structureApi['coordonnees']['adresse_siege']['voie']) ? $structureApi['coordonnees']['adresse_siege']['voie'] : ''
            ]) : $attributes['city'];

        if (isset($structureApi['coordonnees']['adresse_siege']['cp'])) {
            $attributes['zip'] = isset($structureApi['coordonnees']['adresse_siege']['cp']) ? $structureApi['coordonnees']['adresse_siege']['cp'] : null;
            $attributes['department'] = isset($structureApi['coordonnees']['adresse_siege']['cp']) ? substr($structureApi['coordonnees']['adresse_siege']['cp'], 0, 2) : null;
            if ($attributes['department'] == 20) {
                $zip3 = substr($structureApi['coordonnees']['adresse_siege']['cp'], 0, 3);
                if ($zip3 == '200' || $zip3 == '201') {
                    $attributes['department'] = '2A';
                } else {
                    $attributes['department'] = '2B';
                }
            }
            $coordonates = AlgoliaPlacesGeocoder::getCoordinatesForZip($attributes['zip']);
            if ($coordonates) {
                $attributes['latitude'] = $coordonates['latitude'];
                $attributes['longitude'] = $coordonates['longitude'];
            }
        }
        return $attributes;
    }

    public static function prepareStructureDomaines($structureApi)
    {
        $domaine = null;
        if (isset($structureApi['theme'])) {
            switch ($structureApi['theme']) {
                case 'Culture':
                    $domaine = 'Art et culture pour tous';
                    break;
                case 'Education et formation':
                    $domaine = 'Éducation pour tous';
                    break;
                case 'Environnement et patrimoine':
                    $domaine = 'Protection de la nature';
                    break;
                case 'Santé et action sociale':
                    $domaine = 'Santé pour tous';
                    break;
                case 'Sport':
                    $domaine = 'Sport pour tous';
                    break;
            }
        }
        return $domaine;
    }
}
