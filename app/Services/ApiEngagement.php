<?php

namespace App\Services;

use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

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
                fn ($mission) => $this->formatMission($mission),
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
        ])->get('https://api.api-engagement.beta.gouv.fr/v0/mission/' . $id);

        return isset($response['data']) ? $this->formatMission($response['data']) : null;
    }

    public function getMyMission($id)
    {
        $response = Http::withHeaders([
            'apikey' => config('app.api_engagement_key'),
        ])->get('https://api.api-engagement.beta.gouv.fr/v0/mymission/' . $id);

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
        if (strpos($mission['title'], 'Je ramasse des déchets') !== false) {
            return 'Je ramasse des déchets';
        }
        if (strpos($mission['title'], 'Je protège la faune et la flore') !== false) {
            return 'Je protège la faune et la flore';
        }
        if (strpos($mission['title'], 'Je découvre la biodiversité') !== false) {
            return 'Je découvre la biodiversité';
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

    private function formatActivity($mission)
    {
        if (!isset($mission['activity'])) {
            return null;
        }

        switch ($mission['activity']) {
            case 'sante-soins':
                return [
                    'id' => 2,
                    'name' => 'Médical',
                ];
                break;
            case 'alphabetisation':
                return [
                    'id' => 4,
                    'name' => 'Alphabétisation / Apprentissage du français (FLE)',
                ];
                break;
            case 'jardinage':
                return [
                    'id' => 5,
                    'name' => "Aménagement d'espaces naturels",
                ];
                break;
            case 'animation':
                return [
                    'id' => 6,
                    'name' => 'Animation / Loisirs',
                ];
                break;
            case 'collecte':
                return [
                    'id' => 7,
                    'name' => 'Collecte de produits',
                ];
                break;
            case 'mentorat-parrainage':
                return [
                    'id' => 10,
                    'name' => 'Mentorat & Parrainage',
                ];
                break;
            case 'secourisme':
                return [
                    'id' => 14,
                    'name' => 'Secourisme et sécurité civile',
                ];
                break;
            case 'enseignement-formation':
            case 'soutien-scolaire':
                return [
                    'id' => 17,
                    'name' => 'Soutien scolaire et formation',
                ];
                break;
            case 'encadrement-d-equipes':
                return [
                    'id' => 20,
                    'name' => 'Accompagnement aux activités sportives',
                ];
                break;
            case 'accueil-de-public':
                return [
                    'id' => 21,
                    'name' => 'Accueil / Information',
                ];
                break;
            case 'activites-manuelles':
            case 'bricolage':
                return [
                    'id' => 22,
                    'name' => 'Travaux manuels',
                ];
                break;
            case 'communication':
            case 'ecoute-permanence':
                return [
                    'id' => 24,
                    'name' => 'Communication',
                ];
                break;
            case 'gestion-recherche-des-partenariats':
                return [
                    'id' => 26,
                    'name' => 'Recherche de partenariats',
                ];
                break;
            case 'distribution':
                return [
                    'id' => 28,
                    'name' => 'Distribution',
                ];
                break;
            case 'aide-psychologique':
            case 'ecoute-permanence':
                return [
                    'id' => 29,
                    'name' => 'Écoute / Aide psychologique',
                ];
                break;
            case 'comptabilite-finance':
                return [
                    'id' => 30,
                    'name' => 'Gestion financière / comptabilité',
                ];
                break;
            case 'taches-administratives':
                return [
                    'id' => 31,
                    'name' => 'Gestion administrative',
                ];
                break;
            case 'responsabilites-associatives':
                return [
                    'id' => 32,
                    'name' => 'Gouvernance',
                ];
                break;
            case 'logistique':
                return [
                    'id' => 33,
                    'name' => 'Logistique',
                ];
                break;
            case 'documentation-traduction':
                return [
                    'id' => 35,
                    'name' => 'Traduction',
                ];
                break;
            case 'gestion-de-projets':
                return [
                    'id' => 37,
                    'name' => 'Gestion de projets',
                ];
                break;
            case 'recrutement':
                return [
                    'id' => 38,
                    'name' => 'Gestion des ressources humaines',
                ];
                break;
            case 'informatique':
                return [
                    'id' => 39,
                    'name' => 'Informatique',
                ];
                break;
            case 'juridique':
                return [
                    'id' => 41,
                    'name' => 'Droit et conseil juridique',
                ];
                break;
            case 'sensibilisation':
                return [
                    'id' => 19,
                    'name' => 'Actions de sensibilisation',
                ];
                break;
            case 'soins-animaux':
                return [
                    'id' => 16,
                    'name' => 'Soins aux animaux',
                ];
                break;
            case 'ramassage-dechets':
                return [
                    'id' => 12,
                    'name' => 'Ramassage de déchets',
                ];
                break;
            default:
                return null;
                break;
        }
    }

    private function formatMission($mission)
    {
        $domaine = $this->formatDomain($mission);
        $departmentNumber = $this->getDepartmentNumber($mission);
        $departmentName = $this->getDepartmentName($departmentNumber, $mission);
        $id = isset($mission['id']) ? $mission['id'] : $mission['_id'];

        return [
            'provider' => 'api_engagement',
            'objectID' => 'ApiEngagement/' . $id,
            'publisher_name' => $mission['publisherName'],
            'publisher_logo' => $mission['publisherLogo'],
            'publisher_url' => $mission['publisherUrl'] ?? null,
            'application_url' => $mission['applicationUrl'],
            'id' => $id,
            'name' => $mission['title'],
            'addresses' => [
                [
                    'city' => $mission['city'] ?? null,
                    'zip' => $mission['postalCode'] ?? null,
                    'label' => $mission['address'] ?? null,
                    'latitude' => isset($mission['location']) && isset($mission['location']['lat']) ? $mission['location']['lat'] : 0,
                    'longitude' => isset($mission['location']) && isset($mission['location']['lon']) ? $mission['location']['lon'] : 0,
                ],
            ],
            // 'city' => $mission['city'] ?? null,
            'department' => $departmentNumber ?? null,
            'department_name' => $departmentNumber && $departmentName ?
                $departmentNumber . ' - ' . $departmentName : null,
            // 'zip' => $mission['postalCode'] ?? null,
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
                'photo' => null,
            ],
            'domaine_id' => $domaine['id'],
            'domaine' => [
                'id' => $domaine['id'],
                'name' => $domaine['name'],
            ],
            'domaines' => [$this->formatDomain($mission)['name']],
            '_geoloc' => [
                'lat' => isset($mission['location']) && isset($mission['location']['lat']) ? (float)$mission['location']['lat'] : 0,
                'lng' => isset($mission['location']) && isset($mission['location']['lon']) ? (float)$mission['location']['lon'] : 0,
            ],
            'post_date' => strtotime($mission['postedAt']),
            'description' => $mission['description'],
            'start_date' => isset($mission['startAt']) ? strtotime($mission['startAt']) : null,
            'end_date' => isset($mission['endAt']) ? strtotime($mission['endAt']) : null,
            'full_address' => $mission['address'] ?? null,
            'is_outdated' => isset($mission['endAt']) && $mission['endAt'] < Carbon::today() ? true : false, // Fallback to false for sorting purposes
            'is_registration_open' => true, // Fallback to true for sorting purposes
            'activity' => $this->formatActivity($mission),
            'activities' => $this->formatActivity($mission) ? [$this->formatActivity($mission)] : []
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
                ])->put('https://api.api-engagement.beta.gouv.fr/v1/association/' . $structure->rna . '/etablissement/' . $structure->api_id, $attributes);
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
            ])->post('https://api.api-engagement.beta.gouv.fr/v1/association/search', $attributes);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getStatistics($params)
    {
        try {
            $response = Http::accept('application/json')
                ->withHeaders([
                    'apikey' => config('app.api_engagement_key'),
                ])->get('https://api.api-engagement.beta.gouv.fr/v0/view/stats?' . $params);
        } catch (\Throwable $th) {
            throw $th;
        }

        return $response->json();
    }

    public function getDepartmentNumber($mission)
    {
        if (empty($mission['departmentCode'])) {
            return null;
        }

        if (strlen($mission['departmentCode']) === 1) {
            return '0' . $mission['departmentCode'];
        }

        if (empty($mission['departmentName'])) {
            return $mission['departmentCode'];
        }

        switch ($mission['departmentName']) {
            case 'Guadeloupe':
                return '971';
            case 'Martinique':
                return '972';
            case 'Guyane':
                return '973';
            case 'La Réunion':
            case 'LaRéunion':
                return '974';
            case 'Mayotte':
                return '976';
            case 'Polynésie française':
                return '987';
            case 'Nouvelle-Calédonie':
                return '988';
            case 'Corse-du-Sud':
                return '2A';
            case 'Haute-Corse':
                return '2B';
            default:
                return $mission['departmentCode'];
        }
    }

    public function getDepartmentName($departmentNumber, $mission)
    {
        if (empty($departmentNumber)) {
            return null;
        }

        if (!isset(config('taxonomies.departments.terms')[$departmentNumber])) {
            Log::warning('API Engagement : erreur département inexistant', $mission);
            return;
        }

        return config('taxonomies.departments.terms')[$departmentNumber];
    }
}
