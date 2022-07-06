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
            ])->get("https://api.api-engagement.beta.gouv.fr/v0/mission?limit=$limit&skip=$done&domain=environnement,solidarite-insertion,sante,culture-loisirs,education,sport,emploi,humanitaire,animaux,vivre-ensemble,prevention-protection");

            // mémoire et citoyenneté
            // autre


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

    public function getMission($id)
    {
        $response = Http::withHeaders([
            'apikey' => config('app.api_engagement_key'),
        ])->get("https://api.api-engagement.beta.gouv.fr/v0/mission/" . $id);

        return isset($response['data']) ? $this->formatMission($response['data']) : null;
    }

    public function getMyMission($id)
    {
        $response = Http::withHeaders([
            'apikey' => config('app.api_engagement_key'),
        ])->get("https://api.api-engagement.beta.gouv.fr/v0/mymission/" . $id);

        return isset($response['data']) ? $response['data'] : null;
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
        switch ($mission['domain']) {
            case 'environnement':
                return [
                    'id' => 10,
                    'name' => 'Protection de la nature',
                ];
                break;

            case 'solidarite-insertion':
                return [
                    'id' => 7,
                    'name' => 'Solidarité et insertion',
                ];
                break;

            case 'sante':
                return [
                    'id' => 1,
                    'name' => 'Santé pour tous',
                ];
                break;

            case 'culture-loisirs':
                return [
                    'id' => 3,
                    'name' => 'Art et culture pour tous',
                ];
                break;

            case 'education':
                return [
                    'id' => 9,
                    'name' => 'Éducation pour tous',
                ];
                break;

            case 'emploi':
                return [
                    'id' => 7,
                    'name' => 'Solidarité et insertion',
                ];
                break;

            case 'sport':
                return [
                    'id' => 4,
                    'name' => 'Sport pour tous',
                ];
                break;

            case 'humanitaire':
                return [
                    'id' => 7,
                    'name' => 'Solidarité et insertion',
                ];
                break;

            case 'animaux':
                return [
                    'id' => 10,
                    'name' => 'Protection de la nature',
                ];
                break;

            case 'vivre-ensemble':
                return [
                    'id' => 7,
                    'name' => 'Solidarité et insertion',
                ];
                break;

            case 'autre':
                return [
                    'id' => 9,
                    'name' => 'Autre',
                    // 'logo' => ''
                ];
                break;

            case 'prevention-protection':
                return [
                    'id' => 2,
                    'name' => 'Prévention et protection',
                ];
                break;

            default:
                return [
                    'id' => 9,
                    'name' => '',
                    // 'logo' => ''
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
        $domaine = $this->formatDomain($mission);

        return [
            'provider' => 'api_engagement',
            'objectID' => 'ApiEngagement/' . $mission['_id'],
            'publisher_name' => $mission['publisherName'],
            'publisher_logo' => $mission['publisherLogo'],
            'publisher_url' => $mission['publisherUrl'],
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
            'has_places_left' => isset($mission['places']) ? ($mission['places'] > 0 ? true : false) : true, // Fallback to true for sorting purposes
            'structure' => [
                'id' => $mission['organizationId'] ?? null,
                'name' => $mission['organizationName'] ?? null,
                'description' => $mission['organizationDescription'] ?? null,
            ],
            'type' => $this->formatRemote($mission),
            'template_title' => $this->formatTemplateTitle($mission), // @TODO : à retirer quand facet Algolia OK
            'template' => [
                'title' => $this->formatTemplateTitle($mission),
                'photo' => null
            ],
            'domaine_name' => $this->formatDomain($mission)['name'], // @TODO: A retirer
            'domaine_id' => $domaine['id'],
            'domaine' => [
                'id' => $domaine['id'],
                'name' => $domaine['name']
            ],
            'domaines' => [$this->formatDomain($mission)['name']],
            '_geoloc' => [
                'lat' => isset($mission['location']) && isset($mission['location']['lat']) ? $mission['location']['lat'] : 0,
                'lng' => isset($mission['location']) && isset($mission['location']['lon']) ? $mission['location']['lon'] : 0
            ],
            'post_date' => strtotime($mission['postedAt']),
            'description' => $mission['description'],
            'start_date' => $mission['startAt'] ?? null,
            'end_date' => $mission['endAt'] ?? null,
            'full_address' => $mission['adresse'],
        ];
    }

    public static function prepareStructureAttributes($structureApi)
    {
        $attributes = [];
        $attributes['api_id'] = isset($structureApi['_id']) ? $structureApi['_id'] : null;
        $attributes['rna'] = isset($structureApi['rna']) ? $structureApi['rna'] : null;
        $attributes['statut_juridique'] = isset($structureApi['regime']) ? $structureApi['regime'] : null;

        $attributes['city'] = isset($structureApi['coordonnees']['adresse']['commune'])
            ? $structureApi['coordonnees']['adresse']['commune']
            : null;

        $attributes['address'] = isset($structureApi['coordonnees']['adresse']['nom_complet'])
            ? $structureApi['coordonnees']['adresse']['nom_complet']
            : $attributes['city'];

        if (isset($structureApi['coordonnees']['adresse']['code_postal'])) {
            $attributes['zip'] = isset($structureApi['coordonnees']['adresse']['code_postal'])
                ? $structureApi['coordonnees']['adresse']['code_postal']
                : null;

            $attributes['department'] = isset($structureApi['coordonnees']['adresse']['departement_numero']) && isset(config('taxonomies.departments.terms')[$structureApi['coordonnees']['adresse']['departement_numero']])
                ? $structureApi['coordonnees']['adresse']['departement_numero']
                : null;

            $place = ApiAdresse::search(['q' => $attributes['zip'], 'type' => 'municipality', 'limit' => 1]);
            if (!empty($place)) {
                $attributes['latitude'] = $place['geometry']['coordinates'][1];
                $attributes['longitude'] = $place['geometry']['coordinates'][0];
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

    public static function syncAssociation($structure)
    {
        if ($structure->rna && $structure->api_id) {
            try {
                if ($structure->statut_juridique) {
                    $attributes['statut_juridique'] = $structure->statut_juridique;
                }
                if (!empty($structure->domaines)) {
                    $attributes['domaines'] = $structure->domaines->pluck('name')->toArray();
                }
                if (!empty($structure->publics_beneficiaires)) {
                    $termsPublicsBeneficiaires = config('taxonomies.mission_publics_beneficiaires.terms');
                    $attributes['publics_beneficiaires'] = collect($structure->publics_beneficiaires)->map(function ($item) use ($termsPublicsBeneficiaires) {
                        return $termsPublicsBeneficiaires[$item];
                    })->toArray();
                }
                if ($structure->description) {
                    $attributes['description'] = $structure->description;
                }
                if ($structure->logo) {
                    $attributes['logo'] = $structure->logo['original'];
                }
                if ($structure->website) {
                    $attributes['url'] = $structure->website;
                }
                if ($structure->donation) {
                    $attributes['donation'] = $structure->donation;
                }
                if ($structure->facebook) {
                    $attributes['facebook'] = $structure->facebook;
                }
                if ($structure->twitter) {
                    $attributes['twitter'] = $structure->twitter;
                }

                if ($structure->address != $structure->city) {
                    if ($structure->latitude && $structure->longitude) {
                        $attributes['coordonnees']['adresse']['location'] = [
                            'lat' => $structure->latitude,
                            'lon' => $structure->longitude,
                        ];
                    }
                    if ($structure->address) {
                        $attributes['coordonnees']['adresse']['nom'] = $structure->address;
                    }
                    if ($structure->city) {
                        $attributes['coordonnees']['adresse']['commune'] = $structure->city;
                    }
                    if ($structure->zip) {
                        $attributes['coordonnees']['adresse']['code_postal'] = $structure->zip;
                    }
                    if ($structure->department) {
                        $termsDepartments = config('taxonomies.departments.terms');
                        $termsRegions = config('taxonomies.department_region.terms');
                        $attributes['coordonnees']['adresse']['departement_numero'] = $structure->department;
                        $attributes['coordonnees']['adresse']['departement'] = $termsDepartments[$structure->department];
                        $attributes['coordonnees']['adresse']['region'] = $termsRegions[$structure->department];
                    }
                }

                if ($structure->phone) {
                    $attributes['coordonnees']['telephone'] = [$structure->phone];
                }
                if ($structure->email) {
                    $attributes['coordonnees']['courriel'] = [$structure->email];
                }

                // ray($attributes);

                return Http::withHeaders([
                    'apikey' => config('app.api_engagement_key'),
                ])->put("https://api.api-engagement.beta.gouv.fr/v1/association/" . $structure->rna . "/etablissement/" . $structure->api_id, $attributes);
            } catch (\Throwable $th) {
                throw $th;
            }
        }
    }

    public static function findAssociation(array $attributes)
    {

        try {
            return Http::withHeaders([
                'apikey' => config('app.api_engagement_key'),
            ])->post("https://api.api-engagement.beta.gouv.fr/v1/association/search", $attributes);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getStatistics($params)
    {

        try {
            $response = Http::withHeaders([
            'apikey' => config('app.api_engagement_key'),
            ])->get("https://api.api-engagement.beta.gouv.fr/v0/view/stats?" . http_build_query($params));
        } catch (\Throwable $th) {
            throw $th;
        }

        // ray("https://api.api-engagement.beta.gouv.fr/v0/view/stats?" . http_build_query($params));
        // ray($response);

        return isset($response['data']) ? $response['data'] : null;
    }
}
