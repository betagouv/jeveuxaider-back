<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\Territoire;
use Carbon\Carbon;

class ConfigController extends Controller
{
    public function sitemap()
    {
        if (request()->get('type')) {
            switch (request()->get('type')) {
                case 'city':
                    return Territoire::where('type', 'city')
                        ->where('state', 'validated')
                        ->where('is_published', true)
                        ->get()
                        ->map(function ($territoire) {
                            return [
                                'url' => $territoire->full_url,
                                'lastmod' => Carbon::now()->subDays(1),
                            ];
                        });
                case 'department':
                    return Territoire::where('type', 'department')
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
                case 'organization':
                    return Structure::where('state', 'Validée')
                        ->where('statut_juridique', 'Association')
                        ->whereNotNull('slug')
                        ->get()
                        ->map(function ($organisation) {
                            $date = new Carbon($organisation->updated_at);

                            return [
                                'url' => $organisation->full_url,
                                'lastmod' => $date->lt(Carbon::now()->startOfMonth()) ? Carbon::now()->startOfMonth() : $organisation->updated_at,
                            ];
                        });
                case 'mission':
                    return Mission::available()
                        ->get()
                        ->map(function ($mission) {
                            $date = new Carbon($mission->updated_at);

                            return [
                                'url' => $mission->full_url,
                                'lastmod' => $date->lt(Carbon::now()->startOfMonth()) ? Carbon::now()->startOfMonth() : $mission->updated_at,
                            ];
                        });
                case 'reseau':
                    return Reseau::where('is_published', true)
                        ->get()
                        ->map(function ($reseau) {
                            return [
                                'url' => $reseau->full_url,
                                'lastmod' => Carbon::now()->subDays(1),
                            ];
                        });
                case 'activity':
                    return Activity::where('is_published', true)
                        ->get()
                        ->map(function ($reseau) {
                            return [
                                'url' => $reseau->full_url,
                                'lastmod' => Carbon::now()->subDays(1),
                            ];
                        });
            }
        }

        return [
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
                'url' => '/inscription',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/inscription/benevole',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
            [
                'url' => '/inscription/responsable',
                'lastmod' => Carbon::now()->startOfMonth(),
            ],
        ];
    }
}
