<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Mission;
use App\Models\NotificationBenevole;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\StructureScore;
use App\Models\Territoire;
use App\Services\ApiEngagement;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsPublicController extends Controller
{
    public $startDate;
    public $endDate;
    public $department;
    public $startYear;
    public $endYear;

    public function __construct(Request $request)
    {
        $this->startYear = 2020;
        $this->endYear = date('Y');

        if($request->input('start_date')) {
            $this->startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->hour(0)->minute(0)->second(0);
        } else {
            $this->startDate = Carbon::createFromFormat('Y-m-d', '2020-01-01')->hour(0)->minute(0)->second(0);
        }
        if($request->input('end_date')) {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->hour(23)->minute(59)->second(59);
        } else {
            $this->endDate = Carbon::now()->hour(23)->minute(59)->second(59);
        }
        if($request->input('department')) {
            $this->department = $request->input('department');
        }
    }

    public function overviewQuickGlance(Request $request)
    {
        return [
            'organisations' => Structure::whereIn('state', ['Validée'])->whereBetween('created_at', [$this->startDate, $this->endDate])->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count(),
            'missions' => Mission::whereIn('state', ['Validée', 'Terminée'])->whereBetween('created_at', [$this->startDate, $this->endDate])->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count(),
            'participations' => Participation::whereBetween('created_at', [$this->startDate, $this->endDate])->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )->count(),
            'utilisateurs' => Profile::whereBetween('created_at', [$this->startDate, $this->endDate])->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )->count(),
        ];
    }

    public function overviewOrganisations(Request $request)
    {
        $missionsAvailable = Mission::when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )
            ->available()
            ->get();

        return [
            'organisations' => Structure::when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
            'reseaux' => Reseau::where('is_published', true)->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count(),
            'territoires' => Territoire::where('is_published', true)->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count(),
        ];
    }

    public function overviewMissions(Request $request)
    {
        $missionsCount = Mission::when($this->department, function ($query) {
            $query->where('department', $this->department);
        })
        ->available()
        ->count();

        $missionsValidatedOverCount = Mission::whereIn('state', ['Validée', 'Terminée'])
        ->when($this->department, function ($query) {
            $query->where('department', $this->department);
        })
        ->count();

        return [
            'missions_available' => $missionsCount,
            'missions_validated_and_over' => $missionsValidatedOverCount,
            'missions_snu' => Mission::where('is_snu_mig_compatible', true)
                ->whereIn('state', ['Validée', 'Terminée'])
                ->when($this->department, function ($query) {
                    $query->where('department', $this->department);
                })
            ->count(),
        ];
    }

    public function overviewPlaces(Request $request)
    {
        $missionsAvailable = Mission::when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'places' => $placesOffered,
            'places_left' => $placesLeft,
        ];
    }

    public function overviewParticipations(Request $request)
    {
        return [
            'participations' => Participation::when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->count(),
            'messages' => Message::when(
                $this->department,
                function ($query) {
                    $query
                        ->whereHas('conversation', function (Builder $query) {
                            $query->where('conversable_type', 'App\Models\Participation');
                        })
                        ->whereHas(
                            'conversation.conversable',
                            function (Builder $query) {
                                $query->department($this->department);
                            }
                        );
                }
            )->count(),
        ];
    }

    public function overviewUtilisateurs(Request $request)
    {
        return [
            'utilisateurs' => Profile::when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
            'benevoles' => Profile::when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->whereHas(
                'user',
                function (Builder $query) {
                    $query->where('context_role', 'volontaire');
                }
            )->count(),
            'benevoles_visibles_marketplace' => Profile::where('is_visible', true)
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })->count(),
        ];
    }

    public function overviewAPIEngagement(Request $request)
    {
        $service = new ApiEngagement();

        $outgoingTrafic = $service->getStatistics('fromPublisherId=5f5931496c7ea514150a818f&type=click');
        $incomingTrafic = $service->getStatistics('toPublisherId=5f5931496c7ea514150a818f&type=click');
        $outgoingApplies = $service->getStatistics('fromPublisherId=5f5931496c7ea514150a818f&type=apply');
        $incomingApplies = $service->getStatistics('toPublisherId=5f5931496c7ea514150a818f&type=apply');

        return [
            'outgoingTrafic' => $outgoingTrafic['total'],
            'incomingTrafic' => $incomingTrafic['total'],
            'outgoingApplies' => $outgoingApplies['total'],
            'incomingApplies' => $incomingApplies['total'],
        ];
    }

    public function globalOrganisations(Request $request)
    {
        $organisationsCount = Structure::when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $organisationsValidatedCount = Structure::whereIn('state', ['Validée'])->when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'organisations_count' => $organisationsCount,
            'organisations_validated_count' => $organisationsValidatedCount,
            'organisations_conversion_rate' => $organisationsCount ? round(($organisationsValidatedCount / $organisationsCount) * 100) : 0,
            'organisations_response_time_avg' => round(
                StructureScore::whereHas('structure', function (Builder $query) use ($request) {
                    $query->whereIn('state', ['Validée'])->when(
                        $this->department,
                        function ($query) {
                            $query->where('department', $this->department);
                        }
                    )->whereBetween('created_at', [$this->startDate, $this->endDate]);
                })->avg('response_time')
            ),
            'organisations_response_ratio_avg' => round(
                StructureScore::whereHas('structure', function (Builder $query) use ($request) {
                    $query->whereIn('state', ['Validée'])->when(
                        $this->department,
                        function ($query) {
                            $query->where('department', $this->department);
                        }
                    )->whereBetween('created_at', [$this->startDate, $this->endDate]);
                })->avg('processed_participations_rate')
            ),
        ];
    }

    public function organisationsByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT date_trunc('month', structures.created_at) AS created_at,
                date_part('year', structures.created_at) as year,
                date_part('month', structures.created_at) as month,
                count(*) AS count
                FROM structures
                WHERE COALESCE(structures.department,'') ILIKE :department
                AND structures.state IN ('Validée')
                AND structures.deleted_at IS NULL
                GROUP BY date_trunc('month', structures.created_at), year, month
                ORDER BY date_trunc('month', structures.created_at) ASC
            ", [
            'department' => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        $collection = collect($results);

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $item = $collection->where('year', $year)->where('month', $month)->first();
                $items[$year][] = $item ? $item->count : 0;
            }
        }

        return $items;
    }

    public function organisationsByStates(Request $request)
    {
        return [
            'draft' => Structure::where('state', 'Brouillon')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'waiting' => Structure::where('state', 'En attente de validation')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'in_progress' => Structure::where('state', 'En cours de traitement')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'validated' => Structure::where('state', 'Validée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'signaled' => Structure::where('state', 'Signalée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'unsubscribed' => Structure::where('state', 'Désinscrite')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function organisationsByTypes(Request $request)
    {
        return [
            'associations' => Structure::where('state', 'Validée')->where('statut_juridique', 'Association')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'collectivites' => Structure::where('state', 'Validée')->where('statut_juridique', 'Collectivité')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'organisations_publiques' => Structure::where('state', 'Validée')->where('statut_juridique', 'Organisation publique')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'organisations_privees' => Structure::where('state', 'Validée')->where('statut_juridique', 'Organisation privée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function organisationsByReseaux(Request $request)
    {
        $results = DB::table('structures')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->leftJoin('reseaux', 'reseaux.id', '=', 'reseau_structure.reseau_id')
            ->select('reseaux.name', 'reseaux.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('structures.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            ->whereNotNull('reseaux.name')
            ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->groupBy('reseaux.name', 'reseaux.id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function missionsByReseaux(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->leftJoin('reseaux', 'reseaux.id', '=', 'reseau_structure.reseau_id')
            ->select(
                'reseaux.name',
                'reseaux.id',
                DB::raw('COUNT(*) AS count'),
                DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS sum_places_left")
            )
            ->whereNull('structures.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            ->where('structures.state', 'Validée')
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNull('missions.deleted_at')
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->whereNotNull('reseaux.name')
            ->groupBy('reseaux.name', 'reseaux.id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function participationsByReseaux(Request $request)
    {
        $results = DB::table('participations')
            ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->leftJoin('reseaux', 'reseaux.id', '=', 'reseau_structure.reseau_id')
            ->select('reseaux.name', 'reseaux.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('structures.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            ->whereNull('missions.deleted_at')
            ->whereNotNull('reseaux.name')
            ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
            ->groupBy('reseaux.name', 'reseaux.id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get();

        return $results;
    }

    public function globalMissions(Request $request)
    {
        $missionsCount = Mission::when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $missionsValidatedOverCount = Mission::whereIn('state', ['Validée', 'Terminée'])
        ->when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'missions' => $missionsCount,
            'missions_validated_and_over' => $missionsValidatedOverCount,
            // 'missions_conversion_rate' => $missionsCount ? round(($missionsValidatedOverCount / $missionsCount) * 100) : 0,
            'missions_participations_max_sum' => Mission::whereIn('state', ['Validée', 'Terminée'])->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->sum('participations_max'),
            'missions_snu' => Mission::where('is_snu_mig_compatible', true)->whereIn('state', ['Validée', 'Terminée'])->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'missions_snu_participations_max_sum' => Mission::whereIn('state', ['Validée', 'Terminée'])->where('is_snu_mig_compatible', true)->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->sum('snu_mig_places'),
        ];
    }

    public function missionsByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT date_trunc('month', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                date_part('month', missions.created_at) as month,
                count(*) AS count
                FROM missions
                WHERE COALESCE(missions.department,'') ILIKE :department
                AND missions.state IN ('Validée', 'Terminée')
                GROUP BY date_trunc('month', missions.created_at), year, month
                ORDER BY date_trunc('month', missions.created_at) ASC
            ", [
            'department' => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        $collection = collect($results);

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $item = $collection->where('year', $year)->where('month', $month)->first();
                $items[$year][] = $item ? $item->count : 0;
            }
        }

        return $items;
    }

    public function globalParticipations(Request $request)
    {
        $participationsCount = Participation::when(
            $this->department,
            function ($query) {
                $query->department($this->department);
            }
        )
        ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $participationsValidatedCount = Participation::where('state', 'Validée')->when(
            $this->department,
            function ($query) {
                $query->department($this->department);
            }
        )->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'participations' => $participationsCount,
            'participations_validated' => $participationsValidatedCount,
            'messages' => Message::when(
                $this->department,
                function ($query) {
                    $query
                        ->whereHas('conversation', function (Builder $query) {
                            $query->where('conversable_type', 'App\Models\Participation');
                        })
                        ->whereHas(
                            'conversation.conversable',
                            function (Builder $query) {
                                $query->department($this->department);
                            }
                        );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function participationsByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT date_trunc('month', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                date_part('month', participations.created_at) as month,
                count(*) AS count
                FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE COALESCE(missions.department,'') ILIKE :department
                GROUP BY date_trunc('month', participations.created_at), year, month
                ORDER BY date_trunc('month', participations.created_at) ASC
            ", [
            'department' => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        $collection = collect($results);

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $item = $collection->where('year', $year)->where('month', $month)->first();
                $items[$year][] = $item ? $item->count : 0;
            }
        }

        return $items;
    }

    public function globalPlaces(Request $request)
    {
        $missionsAvailable = Mission::when(
            $this->department,
            function ($query) {
                $query->where('department', $this->department);
            }
        )
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'missions_available' => $missionsAvailable->count(),
            'places' => $placesOffered,
            'places_left' => $placesLeft,
        ];
    }

    public function globalUtilisateurs(Request $request)
    {
        return [
            'utilisateurs' => Profile::when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles' => Profile::whereHas(
                'user',
                function (Builder $query) {
                    $query->where('context_role', 'volontaire');
                }
            )->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles_visibles_marketplace' => Profile::where('is_visible', true)->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles_notifications_martketplace' => NotificationBenevole::when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'profile',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function utilisateursByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT date_trunc('month', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                date_part('month', profiles.created_at) as month,
                count(*) AS count
                FROM profiles
                WHERE COALESCE(profiles.department,'') ILIKE :department
                GROUP BY date_trunc('month', profiles.created_at), year, month
                ORDER BY date_trunc('month', profiles.created_at) ASC
            ", [
            'department' => $this->department ? $this->department . '%' : '%%',
        ]);

        $collection = collect($results);

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $item = $collection->where('year', $year)->where('month', $month)->first();
                $items[$year][] = $item ? $item->count : 0;
            }
        }

        return $items;
    }

    public function utilisateursByAge(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT
                    CASE
                        WHEN age(birthday) BETWEEN '16 years'::interval AND '20 years'::interval THEN 'BETWEEN_16_AND_20'
                        WHEN age(birthday) BETWEEN '20 years'::interval AND '25 years'::interval THEN 'BETWEEN_20_AND_25'
                        WHEN age(birthday) BETWEEN '25 years'::interval AND '30 years'::interval THEN 'BETWEEN_25_AND_30'
                        WHEN age(birthday) BETWEEN '30 years'::interval AND '35 years'::interval THEN 'BETWEEN_30_AND_35'
                        WHEN age(birthday) BETWEEN '35 years'::interval AND '40 years'::interval THEN 'BETWEEN_35_AND_40'
                        WHEN age(birthday) BETWEEN '40 years'::interval AND '45 years'::interval THEN 'BETWEEN_40_AND_45'
                        WHEN age(birthday) BETWEEN '45 years'::interval AND '50 years'::interval THEN 'BETWEEN_45_AND_50'
                        WHEN age(birthday) BETWEEN '50 years'::interval AND '55 years'::interval THEN 'BETWEEN_50_AND_55'
                        WHEN age(birthday) BETWEEN '55 years'::interval AND '60 years'::interval THEN 'BETWEEN_55_AND_60'
                        WHEN age(birthday) BETWEEN '60 years'::interval AND '65 years'::interval THEN 'BETWEEN_60_AND_65'
                        WHEN age(birthday) BETWEEN '65 years'::interval AND '70 years'::interval THEN 'BETWEEN_65_AND_70'
                        WHEN age(birthday) BETWEEN '70 years'::interval AND '75 years'::interval THEN 'BETWEEN_70_AND_75'
                        WHEN age(birthday) BETWEEN '75 years'::interval AND '80 years'::interval THEN 'BETWEEN_75_AND_80'
                        ELSE 'MORE_THAN_80'
                    END AS age_range,
                    count(*) AS user_count
                    FROM profiles
                    WHERE age(birthday) > '16 years'::interval AND birthday IS NOT NULL
                    AND COALESCE(profiles.department,'') ILIKE :department
                    AND profiles.created_at BETWEEN :start and :end
                    GROUP BY age_range
                    ORDER BY age_range;
            ", [
            'department' => $this->department ? $this->department . '%' : '%%',
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]);

        return collect($results)->pluck('user_count', 'age_range');
    }

    public function missionsByStates(Request $request)
    {
        return [
            'draft' => Mission::where('state', 'Brouillon')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'waiting' => Mission::where('state', 'En attente de validation')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'in_progress' => Mission::where('state', 'En cours de traitement')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'validated' => Mission::where('state', 'Validée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'finished' => Mission::where('state', 'Terminée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'canceled' => Mission::where('state', 'Annulée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'signaled' => Mission::where('state', 'Signalée')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByTypes(Request $request)
    {
        return [
            'presentiels' => Mission::whereIn('state', ['Validée', 'Terminée'])->where('type', 'Mission en présentiel')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'distances' => Mission::whereIn('state', ['Validée', 'Terminée'])->where('type', 'Mission à distance')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByTemplateTypes(Request $request)
    {
        return [
            'with_template' => Mission::whereIn('state', ['Validée', 'Terminée'])->whereNotNull('template_id')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'without_template' => Mission::whereIn('state', ['Validée', 'Terminée'])->whereNull('template_id')->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->select('structures.name', 'structures.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->whereNotNull('structures.name')
            ->groupBy('structures.name', 'structures.id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function participationsByActivities(Request $request)
    {
        $results = DB::table('participations')
            ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('activities', function ($join) {
                $join->on('activities.id', '=', 'mission_templates.activity_id')
                    ->orOn('activities.id', '=', 'missions.activity_id');
            })
            ->select('activities.name', 'activities.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('participations.deleted_at')
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('activities.name')
            ->groupBy('activities.name', 'activities.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function participationsByOrganisations(Request $request)
    {
        $results = DB::table('participations')
            ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->select('structures.id', 'structures.name', DB::raw('COUNT(*) AS count'))
            ->whereNull('participations.deleted_at')
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('structures.name')
            ->groupBy('structures.id', 'structures.name')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function participationsByDomaines(Request $request)
    {
        $results = DB::table('participations')
            ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('domaines', function ($join) {
                $join->on('domaines.id', '=', 'mission_templates.domaine_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_secondary_id')
                    ->orOn('domaines.id', '=', 'mission_templates.domaine_secondary_id');
            })
            ->select('domaines.name', 'domaines.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
            ->groupBy('domaines.name', 'domaines.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsByActivities(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('activities', function ($join) {
                $join->on('activities.id', '=', 'mission_templates.activity_id')
                    ->orOn('activities.id', '=', 'missions.activity_id');
            })
            ->select('activities.name', 'activities.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->whereNotNull('activities.name')
            ->groupBy('activities.name', 'activities.id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->get();

        return $results;
    }

    public function missionsByDomaines(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('domaines', function ($join) {
                $join->on('domaines.id', '=', 'mission_templates.domaine_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_secondary_id')
                    ->orOn('domaines.id', '=', 'mission_templates.domaine_secondary_id');
            })
            ->select('domaines.name', 'domaines.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->groupBy('domaines.name', 'domaines.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsByTemplates(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->select('mission_templates.title', 'mission_templates.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->whereNotNull('mission_templates.title')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->groupBy('mission_templates.title', 'mission_templates.id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function organisationsByDomaines(Request $request)
    {
        $results = DB::table('structures')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->leftJoin('domainables', function ($join) {
                $join->on('domainables.domainable_id', '=', 'structures.id')
                    ->where('domainables.domainable_type', 'App\Models\Structure');
            })
            ->leftJoin('domaines', 'domaines.id', '=', 'domainables.domaine_id')
            ->select('domaines.name', 'domaines.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('structures.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            ->where('structures.state', 'Validée')
            ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->groupBy('domaines.name', 'domaines.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function utilisateursByDomaines(Request $request)
    {
        $results = DB::table('profiles')
            ->leftJoin('domainables', function ($join) {
                $join->on('domainables.domainable_id', '=', 'profiles.id')
                    ->where('domainables.domainable_type', '=', 'App\Models\Profile');
            })
            ->leftJoin('domaines', 'domaines.id', '=', 'domainables.domaine_id')
            ->select('domaines.name', 'domaines.id', DB::raw('COUNT(*) AS count'))
            ->whereBetween('profiles.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('profiles.department', $this->department);
            })
            ->groupBy('domaines.name', 'domaines.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function utilisateursByActivities(Request $request)
    {
        $results = DB::table('profiles')
            ->leftJoin('activity_profile', 'activity_profile.profile_id', '=', 'profiles.id')
            ->leftJoin('activities', 'activities.id', '=', 'activity_profile.activity_id')
            ->select('activities.name', 'activities.id', DB::raw('COUNT(*) AS count'))
            ->whereBetween('profiles.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('profiles.department', $this->department);
            })
            ->groupBy('activities.name', 'activities.id')
            ->orderByDesc(DB::raw('COUNT(*)'))
            ->limit(5)
            ->get();

        return $results;
    }

    public function participationsCanceledByBenevoles(Request $request)
    {
        return [
            'no_response' => Message::where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'no_response')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'requirements_not_fulfilled' => Message::where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'requirements_not_fulfilled')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'not_available' => Message::where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'not_available')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'other' => Message::where('contextual_state', 'Annulée par bénévole')->where('contextual_reason', 'other')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function participationsRefusedByResponsables(Request $request)
    {
        return [
            'no_response' => Message::where('contextual_state', 'Refusée')->where('contextual_reason', 'no_response')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'requirements_not_fulfilled' => Message::where('contextual_state', 'Refusée')->where('contextual_reason', 'requirements_not_fulfilled')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'mission_terminated' => Message::where('contextual_state', 'Refusée')->where('contextual_reason', 'mission_terminated')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'change_mind' => Message::where('contextual_state', 'Refusée')->where('contextual_reason', 'change_mind')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'other' => Message::where('contextual_state', 'Refusée')->where('contextual_reason', 'other')->when(
                $this->department,
                function ($query) {
                    $query->whereHas(
                        'conversation.conversable',
                        function (Builder $query) {
                            $query->department($this->department);
                        }
                    );
                }
            )->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function participationsDelaysByRegistrations(Request $request)
    {
        $results = DB::select(
            "
            SELECT
            COUNT(date_difference),
            CASE
                WHEN date_difference <= 60 THEN 'LESS_THAN_1_MIN'
                WHEN date_difference BETWEEN 61 AND 300 THEN 'LESS_THAN_5_MIN'
                WHEN date_difference BETWEEN 301 AND 3600 THEN 'LESS_THAN_1_HOUR'
                WHEN date_difference BETWEEN 3601 AND 84600 THEN 'LESS_THAN_1_DAY'
                WHEN date_difference BETWEEN 84601 AND 423000 THEN 'LESS_THAN_5_DAYS'
                WHEN date_difference > 423000 THEN 'OTHER'
            END delay
            FROM (
                SELECT DISTINCT ON (participations.profile_id)
                participations.profile_id, participations.created_at, profiles.email, profiles.created_at,
                EXTRACT(EPOCH FROM (participations.created_at - profiles.created_at)) AS date_difference
                FROM participations
                LEFT JOIN profiles ON profiles.id = participations.profile_id
                WHERE profiles.created_at BETWEEN :start and :end
                AND profiles.zip ILIKE :department
                ORDER BY participations.profile_id, participations.id ASC
            ) MyTable
            GROUP BY delay
            ",
            [
                'department' => $this->department ? $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return collect($results)->pluck('count', 'delay');
    }

    public function placesByReseaux(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->leftJoin('reseaux', 'reseaux.id', '=', 'reseau_structure.reseau_id')
            ->select('reseaux.name', 'reseaux.id', DB::raw('SUM(CASE WHEN missions.state IN (\'Validée\') THEN missions.places_left ELSE 0 END) AS count'))
            ->whereNull('structures.deleted_at')
            ->where('structures.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereNull('missions.deleted_at')
            ->where('missions.is_registration_open', true)
            ->where('missions.is_online', true)
            ->whereNotNull('reseaux.name')
            ->groupBy('reseaux.name', 'reseaux.id')
            ->orderByDesc('count')
            ->limit(5)
            ->get();

        return $results;
    }

    public function placesByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->select('structures.name', 'structures.id', DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count"))
            ->whereNull('structures.deleted_at')
            ->where('structures.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->whereNull('missions.deleted_at')
            ->where('missions.is_registration_open', true)
            ->where('missions.is_online', true)
            ->whereNotNull('structures.name')
            ->groupBy('structures.name', 'structures.id')
            ->orderByDesc(DB::raw('SUM(CASE WHEN missions.state IN (\'Validée\') THEN missions.places_left ELSE 0 END)'))
            ->limit(5)
            ->get();

        return $results;
    }

    public function placesByMissions(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->select(
                'missions.name',
                'missions.id',
                'mission_templates.title',
                DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count")
            )
            ->whereNull('structures.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->where('missions.is_registration_open', true)
            ->where('missions.is_online', true)
            ->where('structures.state', 'Validée')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('structures.name')
            ->groupBy('missions.name', 'missions.id', 'mission_templates.title')
            ->orderByDesc(DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END)"))
            ->limit(5)
            ->get();

        return $results;
    }

    public function placesByDomaines(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('domaines', function ($join) {
                $join->on('domaines.id', '=', 'mission_templates.domaine_id')
                    ->orOn('domaines.id', '=', 'mission_templates.domaine_secondary_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_id')
                    ->orOn('domaines.id', '=', 'missions.domaine_secondary_id');
            })
            ->select('domaines.name', 'domaines.id', DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count"))
            ->whereNull('missions.deleted_at')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->where('missions.is_registration_open', true)
            ->where('missions.is_online', true)
            ->whereNotNull('domaines.name')
            ->whereNull('structures.deleted_at')
            ->where('structures.state', 'Validée')
            ->groupBy('domaines.name', 'domaines.id')
            ->orderByDesc(DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END)"))
            ->get();

        return $results;
    }

    public function placesByActivities(Request $request)
    {
        $results = DB::table('missions')
            ->select(
                'activities.name',
                'activities.id',
                DB::raw("SUM(CASE WHEN missions.state IN ('Validée') THEN missions.places_left ELSE 0 END) AS count")
            )
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->leftJoin('mission_templates', 'mission_templates.id', '=', 'missions.template_id')
            ->leftJoin('activities', function ($join) {
                $join->on('activities.id', '=', 'mission_templates.activity_id')
                    ->orOn('activities.id', '=', 'missions.activity_id')
                    ->orOn('activities.id', '=', 'mission_templates.activity_secondary_id')
                    ->orOn('activities.id', '=', 'missions.activity_secondary_id');
            })
            ->whereNull('missions.deleted_at')
            ->where('missions.is_registration_open', true)
            ->where('missions.is_online', true)
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->whereNotNull('activities.name')
            ->whereNull('structures.deleted_at')
            ->where('structures.state', 'Validée')
            ->groupBy('activities.name', 'activities.id')
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function temoignagesByGrades()
    {
        $results = DB::select(
            "
                SELECT COUNT(*), grade, COUNT(*) * 1.0 / SUM(COUNT(*)) OVER () As percent
                FROM temoignages
                LEFT JOIN participations ON participations.id = temoignages.participation_id
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE temoignages.created_at BETWEEN :start and :end
                AND COALESCE(missions.department,'') ILIKE :department
                GROUP BY grade
                ORDER by grade DESC
            ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

}
