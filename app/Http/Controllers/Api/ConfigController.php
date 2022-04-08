<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Territoire;
use App\Models\Structure;
use App\Models\Mission;
use App\Models\Reseau;
use App\Models\Thematique;
use Carbon\Carbon;

class ConfigController extends Controller
{

    public function sitemap()
    {

        $pagesUrls = [
            [
                'url' => '/',
                'lastmod' => Carbon::now()->subDays(7),
            ],
            [
                'url' => '/missions-benevolat',
                'lastmod' => Carbon::now()->subDays(1),
            ],
            [
                'url' => '/territoires',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/regles-de-securite',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/inscription',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/register/volontaire',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/inscription/organisation',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
        ];

        $missionsUrls = Mission::available()
            ->get()
            ->map(function ($mission) {
                $date = new Carbon($mission->updated_at);
                return [
                    'url' => $mission->full_url,
                    'lastmod' => $date->lt(Carbon::now()->startOfMonth()) ? Carbon::now()->startOfMonth() : $mission->updated_at,
                ];
            });

        $departementsUrls = Territoire::where('type', 'department')
            ->where('state', 'validated')
            ->where('is_published', true)
            ->get()
            ->map(function ($territoire) {
                $date = new Carbon($territoire->updated_at);
                return [
                    'url' => $territoire->full_url,
                    'lastmod' => $date->lt(Carbon::now()->startOfMonth()) ? Carbon::now()->startOfMonth() : $territoire->updated_at,
                ];
            });

        $citiesUrls = Territoire::where('type', 'city')
            ->where('state', 'validated')
            ->where('is_published', true)
            ->get()
            ->map(function ($territoire) {
                return [
                    'url' => $territoire->full_url,
                    'lastmod' => Carbon::now()->subDays(1),
                ];
            });

        $domainesUrls = Thematique::where('published', true)
            ->get()
            ->map(function ($domaine) {
                return [
                    'url' => $domaine->full_url,
                    'lastmod' => Carbon::now()->subDays(1),
                ];
            });

        $organisationsUrls = Structure::where('state', 'Validée')
            ->where('statut_juridique', 'Association')
            ->whereNotNull('slug')
            ->get()
            ->map(function ($organisation) {
                return [
                    'url' => $organisation->full_url,
                    'lastmod' => Carbon::now()->subDays(1),
                ];
            });

        $reseauxUrls = Reseau::where('is_published', true)
            ->get()
            ->map(function ($reseau) {
                return [
                    'url' => $reseau->full_url,
                    'lastmod' => Carbon::now()->subDays(1),
                ];
            });

        return [
            ...$pagesUrls,
            ...$missionsUrls,
            ...$departementsUrls,
            ...$citiesUrls,
            ...$domainesUrls,
            ...$organisationsUrls,
            ...$reseauxUrls,
        ];
    }
}
