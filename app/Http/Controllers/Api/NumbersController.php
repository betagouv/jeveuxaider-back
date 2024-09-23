<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NumbersController extends Controller
{
    public $startDate = null;
    public $endDate = null;
    public $department = null;
    public $region = null;
    public $structureId = null;
    public $reseauId = null;

    public function __construct(Request $request)
    {
        if($request->header('Context-Role') == 'responsable') {
            $structure = Structure::find(Auth::guard('api')->user()->contextable_id);
            $this->structureId = Auth::guard('api')->user()->contextable_id;
        } else {
            $this->structureId = $request->input('structure');
        }

        if($request->header('Context-Role') == 'tete_de_reseau') {
            $reseau = Reseau::find(Auth::guard('api')->user()->contextable_id);
            $this->reseauId = Auth::guard('api')->user()->contextable_id;
        } else {
            $this->reseauId = $request->input('reseau');
        }

        if($request->input('start_date')) {
            $this->startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->hour(0)->minute(0)->second(0);
        } else {
            if($request->header('Context-Role') === 'responsable') {
                $this->startDate = $structure->created_at->format('Y-m-d');
            } elseif($request->header('Context-Role') === 'tete_de_reseau') {
                $this->startDate = $reseau->created_at->format('Y-m-d');
            } else {
                $this->startDate = Carbon::createFromFormat('Y-m-d', '2020-03-01')->hour(0)->minute(0)->second(0);
            }
        }
        if($request->input('end_date')) {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->hour(23)->minute(59)->second(59);
        } else {
            $this->endDate = Carbon::now()->hour(23)->minute(59)->second(59);
        }

        if($request->header('Context-Role') == 'referent') {
            $this->department = Auth::guard('api')->user()->departmentsAsReferent->first()->number;
        } elseif($request->input('department')) {
            $this->department = $request->input('department');
        }

        if($request->has('region')) {
            $this->region = $request->input('region');
        }
    }

    public function overviewQuickGlance(Request $request)
    {
        if(in_array($request->header('Context-Role'), ['admin', 'referent'])) {
            $organisationsValidatedCount = Structure::role($request->header('Context-Role'))
            ->whereIn('state', ['Validée'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count();


            $profilesCount = Profile::role($request->header('Context-Role'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )->count();
        }

        $missionsValidatedCount = Mission::role($request->header('Context-Role'))
            ->whereIn('state', ['Validée', 'Terminée'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when(
                $this->department,
                function ($query) {
                    $query->where('department', $this->department);
                }
            )->count();

        $participationsCount = Participation::role($request->header('Context-Role'))
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )->count();

        $participationsValidatedCount = Participation::role($request->header('Context-Role'))
            ->whereIn('state', ['Validée'])
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )->count();

        return [
            'organisations' => isset($organisationsValidatedCount) ? $organisationsValidatedCount : null,
            'missions' => isset($missionsValidatedCount) ? $missionsValidatedCount : null,
            'participations' => isset($participationsCount) ? $participationsCount : null,
            'participations_validated' => isset($participationsValidatedCount) ? $participationsValidatedCount : null,
            'utilisateurs' =>  isset($profilesCount) ? $profilesCount : null,
        ];
    }

    public function overviewPlaces(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'places' => $placesOffered,
            'places_left' => $placesLeft,
            'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        ];
    }

    public function overviewParticipations(Request $request)
    {
        $queryBuilder = Participation::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $participationsCount =  (clone $queryBuilder)->count();
        $participationsValidatedCount =  (clone $queryBuilder)->where('state', 'Validée')->count();
        $participationsInProgressCount = (clone $queryBuilder)->whereIn('state', ['En attente de validation', 'En cours de traitement'])->count();

        return [
            'participations' => $participationsCount,
            'participations_validated' => $participationsValidatedCount,
            'participations_in_progress' => $participationsInProgressCount,
            'participations_conversion_rate' => $participationsCount ? round(($participationsValidatedCount / $participationsCount) * 100) : 0,
        ];
    }

    public function overviewUtilisateurs(Request $request)
    {
        $queryBuilder = Profile::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);


        return [
            'utilisateurs' => (clone $queryBuilder)->count(),
            'benevoles' => (clone $queryBuilder)->whereHas(
                'user',
                function (Builder $query) {
                    $query->where('context_role', 'volontaire');
                }
            )->count(),
            'benevoles_actifs' => (clone $queryBuilder)->whereHas('participations')->count(),
            'benevoles_visibles_marketplace' => (clone $queryBuilder)->where('is_visible', true)->count(),
        ];
    }

    public function overviewMissions(Request $request)
    {
        $queryBuilder = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $missionsCount = (clone $queryBuilder)->count();
        $missionsAvailableCount = (clone $queryBuilder)->available()->count();
        $missionsValidatedOverCount = (clone $queryBuilder)->whereIn('state', ['Validée', 'Terminée'])->count();
        $missionsSnuCount = (clone $queryBuilder)->where('is_snu_mig_compatible', true)->whereIn('state', ['Validée', 'Terminée'])->count();

        return [
            'missions' => $missionsCount,
            'missions_available' => $missionsAvailableCount,
            'missions_validated_and_over' => $missionsValidatedOverCount,
            'missions_snu' => $missionsSnuCount,
        ];
    }

    public function overviewOrganisations(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->available();

        $organisationsCount = Structure::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        $organisationsActivesCount = $missionsAvailable->pluck('structure_id')->unique()->count();

        $reseauxCount = Reseau::where('is_published', true)
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        $territoiresCount = Territoire::where('is_published', true)
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->count();

        return [
            'organisations' => $organisationsCount,
            'organisations_actives' => $organisationsActivesCount,
            'reseaux' => $reseauxCount,
            'territoires' => $territoiresCount,
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

    public function overviewAPIEngagementEntrant(Request $request)
    {
        $service = new ApiEngagement();

        $from = $this->startDate->format('Y-m-d');
        $to = $this->endDate->format('Y-m-d');

        $periodRedirections = $service->getStatistics("toPublisherId=5f5931496c7ea514150a818f&type=click&createdAt=gt:$from&createdAt=lt:$to");
        $totalRedirections = $service->getStatistics("toPublisherId=5f5931496c7ea514150a818f&type=click");
        $periodApplications = $service->getStatistics("toPublisherId=5f5931496c7ea514150a818f&type=apply&createdAt=gt:$from&createdAt=lt:$to");
        $totalApplications = $service->getStatistics("toPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'periodRedirections' => $periodRedirections['total'] ?? 0,
            'totalRedirections' => $totalRedirections['total'] ?? 0,
            'periodApplications' => $periodApplications['total'] ?? 0,
            'totalApplications' => $totalApplications['total'] ?? 0,
        ];
    }

    public function overviewAPIEngagementEntrantDetails(Request $request)
    {
        $service = new ApiEngagement();

        $from = $this->startDate->format('Y-m-d');
        $to = $this->endDate->format('Y-m-d');

        $redirections = $service->getStatistics("size=100&facets=fromPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=click");
        $applications = $service->getStatistics("size=100&facets=fromPublisherName&createdAt=gt:$from&createdAt=lt:$to&toPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'redirections' => collect($redirections['facets']['fromPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'redirections' => $item['doc_count'],
                    ];
                }
            )->toArray(),
            'applications' => collect($applications['facets']['fromPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'applications' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }

    public function overviewAPIEngagementSortant(Request $request)
    {
        $service = new ApiEngagement();

        $from = $this->startDate->format('Y-m-d');
        $to = $this->endDate->format('Y-m-d');

        $periodRedirections = $service->getStatistics("fromPublisherId=5f5931496c7ea514150a818f&type=click&createdAt=gt:$from&createdAt=lt:$to");
        $totalRedirections = $service->getStatistics("fromPublisherId=5f5931496c7ea514150a818f&type=click");
        $periodApplications = $service->getStatistics("fromPublisherId=5f5931496c7ea514150a818f&type=apply&createdAt=gt:$from&createdAt=lt:$to");
        $totalApplications = $service->getStatistics("fromPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'periodRedirections' => $periodRedirections['total'] ?? 0,
            'totalRedirections' => $totalRedirections['total'] ?? 0,
            'periodApplications' => $periodApplications['total'] ?? 0,
            'totalApplications' => $totalApplications['total'] ?? 0,
        ];
    }

    public function overviewAPIEngagementSortantDetails(Request $request)
    {
        $service = new ApiEngagement();

        $from = $this->startDate->format('Y-m-d');
        $to = $this->endDate->format('Y-m-d');

        $redirections = $service->getStatistics("size=100&facets=toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=click");
        $applications = $service->getStatistics("size=100&facets=toPublisherName&createdAt=gt:$from&createdAt=lt:$to&fromPublisherId=5f5931496c7ea514150a818f&type=apply");

        return [
            'redirections' => collect($redirections['facets']['toPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'redirections' => $item['doc_count'],
                    ];
                }
            )->toArray(),
            'applications' => collect($applications['facets']['toPublisherName'])->map(
                function ($item) {
                    return [
                        'name' => $item['key'],
                        'applications' => $item['doc_count'],
                    ];
                }
            )->toArray(),
        ];
    }

    public function globalOrganisations(Request $request)
    {
        $queryBuilder = Structure::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $organisationsCount = (clone $queryBuilder)->count();
        $organisationsValidatedCount = (clone $queryBuilder)->whereIn('state', ['Validée'])->count();

        $queryBuilderScore = StructureScore::whereHas('structure', function (Builder $query) use ($request) {
            $query->role($request->header('Context-Role'))->whereIn('state', ['Validée'])
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
        });

        return [
            'organisations_count' => $organisationsCount,
            'organisations_validated_count' => $organisationsValidatedCount,
            'organisations_conversion_rate' => $organisationsCount ? round(($organisationsValidatedCount / $organisationsCount) * 100) : 0,
            'organisations_response_time_avg' => round((clone  $queryBuilderScore)->avg('response_time')),
            'organisations_response_ratio_avg' => round((clone  $queryBuilderScore)->avg('processed_participations_rate')),
        ];
    }

    public function organisationsByStates(Request $request)
    {
        $queryBuilder = Structure::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'draft' => (clone $queryBuilder)->where('state', 'Brouillon')->count(),
            'waiting' => (clone $queryBuilder)->where('state', 'En attente de validation')->count(),
            'in_progress' => (clone $queryBuilder)->where('state', 'En cours de traitement')->count(),
            'validated' => (clone $queryBuilder)->where('state', 'Validée')->count(),
            'signaled' => (clone $queryBuilder)->where('state', 'Signalée')->count(),
            'unsubscribed' => (clone $queryBuilder)->where('state', 'Désinscrite')->count(),
        ];
    }

    public function organisationsByTypes(Request $request)
    {
        $queryBuilder = Structure::role($request->header('Context-Role'))
            ->where('state', 'Validée')
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'associations' => (clone $queryBuilder)->where('statut_juridique', 'Association')->count(),
            'collectivites' => (clone $queryBuilder)->where('statut_juridique', 'Collectivité')->count(),
            'organisations_publiques' => (clone $queryBuilder)->where('statut_juridique', 'Organisation publique')->count(),
            'organisations_privees' => (clone $queryBuilder)->where('statut_juridique', 'Organisation privée')->count(),
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
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
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
            ->when($this->structureId, function ($query) {
                $query->where('structures.id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
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
            ->when($this->structureId, function ($query) {
                $query->where('structures.id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
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
        $queryBuilder = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $missionsCount = (clone $queryBuilder)->count();
        $missionsValidatedOverCount = (clone $queryBuilder)->whereIn('state', ['Validée', 'Terminée'])->count();
        $missionsParticipationsMaxSum = (clone $queryBuilder)->whereIn('state', ['Validée', 'Terminée'])->sum('participations_max');
        $missionsSnuCount = (clone $queryBuilder)->where('is_snu_mig_compatible', true)->whereIn('state', ['Validée', 'Terminée'])->count();
        $missionSnuParticipationsMaxSum = (clone $queryBuilder)->where('is_snu_mig_compatible', true)->whereIn('state', ['Validée', 'Terminée'])->sum('snu_mig_places');

        return [
            'missions' => $missionsCount,
            'missions_validated_and_over' => $missionsValidatedOverCount,
            // 'missions_conversion_rate' => $missionsCount ? round(($missionsValidatedOverCount / $missionsCount) * 100) : 0,
            'missions_participations_max_sum' => $missionsParticipationsMaxSum,
            'missions_snu' => $missionsSnuCount,
            'missions_snu_participations_max_sum' => $missionSnuParticipationsMaxSum,
        ];
    }

    public function globalParticipations(Request $request)
    {
        $queryBuilder = Participation::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        $participationsCount = (clone $queryBuilder)->count();
        $participationsValidatedCount =  (clone $queryBuilder)->where('state', 'Validée')->count();
        $participationsInProgressCount =  (clone $queryBuilder)->whereIn('state', ['En attente de validation', 'En cours de traitement'])->count();

        return [
            'participations' => $participationsCount,
            'participations_validated' => $participationsValidatedCount,
            'participations_in_progress' => $participationsInProgressCount,
            'participations_conversion_rate' => $participationsCount ? round(($participationsValidatedCount / $participationsCount) * 100) : 0,
        ];
    }

    public function globalPlaces(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
            'missions_available' => $missionsAvailable->count(),
            'places' => $placesOffered,
            'places_left' => $placesLeft,
            'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        ];
    }

    public function globalUtilisateurs(Request $request)
    {
        $usersWithParticipations = Profile::role($request->header('Context-Role'))->when(
            $this->department,
            function ($query) {
                $query->department($this->department);
            }
        )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->has('participations')->count();
        $participationsCount = Participation::role($request->header('Context-Role'))->when(
            $this->department,
            function ($query) {
                $query->department($this->department);
            }
        )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count();

        return [
            'utilisateurs' => Profile::role($request->header('Context-Role'))->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
            'benevoles' => Profile::role($request->header('Context-Role'))->whereHas(
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
            'benevoles_actifs' => $usersWithParticipations,
            'benevoles_visibles_marketplace' => Profile::role($request->header('Context-Role'))->where('is_visible', true)->when(
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
            'utilisateurs_with_participations' => $usersWithParticipations,
            'participations_avg' => $usersWithParticipations ? round($participationsCount / $usersWithParticipations, 1) : 0,
            'utilisateurs_archived' => Profile::role($request->header('Context-Role'))->when(
                $this->department,
                function ($query) {
                    $query->department($this->department);
                }
            )
            ->whereHas(
                'user',
                function (Builder $query) {
                    $query->whereBetween('archived_at', [$this->startDate, $this->endDate]);
                }
            )
            ->count(),
        ];
    }

    public function participationsByStates(Request $request)
    {
        $queryBuilder = Participation::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'waiting' =>  (clone $queryBuilder)->where('state', 'En attente de validation')->count(),
            'in_progress' => (clone $queryBuilder)->where('state', 'En cours de traitement')->count(),
            'validated' => (clone $queryBuilder)->where('state', 'Validée')->count(),
            'refused' => (clone $queryBuilder)->where('state', 'Refusée')->count(),
            'canceled' => (clone $queryBuilder)->where('state', 'Annulée')->count(),
        ];
    }

    public function missionsByStates(Request $request)
    {
        $queryBuilder = Mission::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'draft' => (clone $queryBuilder)->where('state', 'Brouillon')->count(),
            'waiting' => (clone $queryBuilder)->where('state', 'En attente de validation')->count(),
            'in_progress' => (clone $queryBuilder)->where('state', 'En cours de traitement')->count(),
            'validated' => (clone $queryBuilder)->where('state', 'Validée')->count(),
            'finished' => (clone $queryBuilder)->where('state', 'Terminée')->count(),
            'canceled' => (clone $queryBuilder)->where('state', 'Annulée')->count(),
            'signaled' => (clone $queryBuilder)->where('state', 'Signalée')->count(),
        ];
    }

    public function missionsByTypes(Request $request)
    {
        $queryBuilder = Mission::role($request->header('Context-Role'))
            ->whereIn('state', ['Validée', 'Terminée'])
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'presentiels' => (clone $queryBuilder)->where('type', 'Mission en présentiel')->count(),
            'distances' => (clone $queryBuilder)->where('type', 'Mission à distance')->count(),
        ];
    }

    public function missionsByTemplateTypes(Request $request)
    {
        $queryBuilder = Mission::role($request->header('Context-Role'))
            ->whereIn('state', ['Validée', 'Terminée'])
            ->when($this->department, function ($query) {
                $query->department($this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->ofStructure($this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->ofReseau($this->reseauId);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate]);

        return [
            'with_template' =>  (clone $queryBuilder)->whereNotNull('template_id')->count(),
            'without_template' =>  (clone $queryBuilder)->whereNull('template_id')->count(),
        ];
    }

    public function missionsByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->select('structures.name', 'structures.id', DB::raw('COUNT(*) AS count'))
            ->whereNull('missions.deleted_at')
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
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
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function utilisateursByAge(Request $request)
    {
        // Generate the age range series (5-year intervals)
        $ageRangeSeries = DB::table(DB::raw('generate_series(0, 100, 5) AS age_range'));

        // Perform the query with the necessary joins and conditions
        $results = DB::table('profiles')
            ->rightJoinSub($ageRangeSeries, 'age_range_series', function ($join) {
                $join->on(DB::raw('FLOOR(EXTRACT(YEAR FROM AGE(profiles.birthday)) / 5) * 5'), '=', 'age_range_series.age_range')
                    ->whereNotNull('profiles.birthday')
                    ->when($this->department, function ($query) {
                        $query->where('department', $this->department);
                    })
                    ->whereBetween('profiles.created_at', [$this->startDate, $this->endDate]);
            })
            ->select(
                'age_range_series.age_range',
                DB::raw('COUNT(profiles.id) AS count')
            )
            ->groupBy('age_range_series.age_range')
            ->orderBy('age_range_series.age_range')
            ->get();

        return $results;
    }

    public function utilisateursWithParticipations(Request $request)
    {
        return [
            'with_participations' => Profile::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->has('participations')->count(),
            'without_participations' => Profile::role($request->header('Context-Role'))
            ->when($this->department, function ($query) {
                $query->where('department', $this->department);
            })
            ->whereBetween('created_at', [$this->startDate, $this->endDate])->doesntHave('participations')->count(),
        ];
    }

    public function missionsOutdatedByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->select(['structures.name', 'structures.id'])
            ->selectRaw('count(*) as count')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->whereNotNull('missions.end_date')
            ->whereNull('missions.deleted_at')
            ->whereNull('structures.deleted_at')
            ->where('missions.end_date', '<', date('Y-m-d'))
            ->where('missions.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->orderByDesc('count')
            ->groupBy(['structures.name', 'structures.id'])
            ->limit(100)
            ->get();

        return $results;
    }

    public function participationsWaitingByOrganisations(Request $request)
    {
        $results = DB::select(
            "
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En attente de validation'
            AND missions.deleted_at IS NULL
            AND COALESCE(missions.department,'') ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function participationsRefusedByOrganisations(Request $request)
    {
        $results = DB::select(
            "
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'Refusée'
            AND missions.deleted_at IS NULL
            AND COALESCE(missions.department,'') ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function participationsCanceledByOrganisations(Request $request)
    {
        $results = DB::select(
            "
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'Annulée'
            AND missions.deleted_at IS NULL
            AND COALESCE(missions.department,'') ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function participationsInProgressByOrganisations(Request $request)
    {
        $results = DB::select(
            "
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En cours de traitement'
            AND missions.deleted_at IS NULL
            AND COALESCE(missions.department,'') ILIKE :department
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function organisationsWaitingByDepartments(Request $request)
    {
        $results = DB::table('structures')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('structures.department', 'ILIKE', $this->department);
            })
            // ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function organisationsInProgressByDepartments(Request $request)
    {
        $results = DB::table('structures')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('structures.department', 'ILIKE', $this->department);
            })
            // ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsWaitingByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsInProgressByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsOutdatedByDepartments(Request $request)
    {
        $results = DB::table('missions')
            ->select(['territoires.id', 'territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->whereNotNull('missions.end_date')
            ->where('territoires.type', 'department')
            ->where('missions.end_date', '<', date('Y-m-d'))
            ->where('missions.state', 'Validée')
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            // ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id', 'territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function participationsCanceledByBenevoles(Request $request)
    {
        $queryBuilder = Message::where('contextual_state', 'Annulée par bénévole')
            ->whereHas('conversation', function (Builder $query) use ($request) {
                $query
                    ->where('conversable_type', 'App\Models\Participation')
                    ->whereHasMorph('conversable', ['App\Models\Participation'], function (Builder $query) use ($request) {
                        $query
                            ->role($request->header('Context-Role'))
                            ->when($this->department, function ($query) {
                                $query->department($this->department);
                            })
                            ->when($this->structureId, function ($query) {
                                $query->ofStructure($this->structureId);
                            })
                            ->when($this->reseauId, function ($query) {
                                $query->ofReseau($this->reseauId);
                            })
                            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    });
            })->distinct('conversation_id');


        return [
            'no_response' => (clone $queryBuilder)->where('contextual_reason', 'no_response')->count(),
            'requirements_not_fulfilled' => (clone $queryBuilder)->where('contextual_reason', 'requirements_not_fulfilled')->count(),
            'not_available' => (clone $queryBuilder)->where('contextual_reason', 'not_available')->count(),
            'other' => (clone $queryBuilder)->where('contextual_reason', 'other')->count(),
        ];
    }

    public function participationsRefusedByResponsables(Request $request)
    {
        $queryBuilder = Message::where('contextual_state', 'Refusée')
            ->whereHas('conversation', function (Builder $query) use ($request) {
                $query
                    ->where('conversable_type', 'App\Models\Participation')
                    ->whereHasMorph('conversable', ['App\Models\Participation'], function (Builder $query) use ($request) {
                        $query
                            ->role($request->header('Context-Role'))
                            ->where('state', 'Refusée')
                            ->when($this->department, function ($query) {
                                $query->department($this->department);
                            })
                            ->when($this->structureId, function ($query) {
                                $query->ofStructure($this->structureId);
                            })
                            ->when($this->reseauId, function ($query) {
                                $query->ofReseau($this->reseauId);
                            })
                            ->whereBetween('created_at', [$this->startDate, $this->endDate]);
                    });
            })->distinct('conversation_id');

        return [
            'no_response' => (clone $queryBuilder)->where('contextual_reason', 'no_response')->count(),
            'requirements_not_fulfilled' => (clone $queryBuilder)->where('contextual_reason', 'requirements_not_fulfilled')->count(),
            'mission_terminated' => (clone $queryBuilder)->where('contextual_reason', 'mission_terminated')->count(),
            'change_mind' => (clone $queryBuilder)->where('contextual_reason', 'change_mind')->count(),
            'other' => (clone $queryBuilder)->where('contextual_reason', 'other')->count(),
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
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
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
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
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
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
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
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
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

    public function structuresByMonth(Request $request)
    {
        // $results = DB::select(
        //     "
        //     SELECT date_trunc('month', structures.created_at) AS created_at,
        //         date_part('year', structures.created_at) as year,
        //         date_part('month', structures.created_at) as month,
        //         count(*) AS structures_total,
        //         sum(case when structures.state  = 'Brouillon' then 1 else 0 end) as structures_draft,
        //         sum(case when structures.state  = 'En attente de validation' then 1 else 0 end) as structures_waiting_validation,
        //         sum(case when structures.state  = 'En cours de traitement' then 1 else 0 end) as structures_being_processed,
        //         sum(case when structures.state  = 'Validée' then 1 else 0 end) as structures_validated,
        //         sum(case when structures.state  = 'Signalée' then 1 else 0 end) as structures_signaled,
        //         sum(case when structures.state  = 'Désinscrite' then 1 else 0 end) as structures_unsubscribed
        //     FROM structures
        //     WHERE structures.deleted_at IS NULL
        //     AND COALESCE(structures.department,'') ILIKE :department
        //     GROUP BY date_trunc('month', structures.created_at), year, month
        //     ORDER BY date_trunc('month', structures.created_at) DESC
        //     ",
        //     [
        //         'department' => $this->department ? '%' . $this->department . '%' : '%%',
        //     ]
        // );

        // foreach ($results as $index => $item) {
        //     if (isset($results[$index + 12])) {
        //         if($results[$index + 12]->structures_validated) {
        //             $results[$index]->structures_validated_variation = (($item->structures_validated - $results[$index + 12]->structures_validated) / $results[$index + 12]->structures_validated) * 100;
        //         }
        //     }
        // }

        // return $results;

        // Subquery to get counts of structures per year
        $structuresSubquery = DB::table('structures')
        ->select(
            DB::raw("date_trunc('month', structures.created_at) AS created_at"),
            DB::raw("COUNT(*) AS structures_validated"),
        )
        ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
        ->whereIn('structures.state', ['Validée', 'Terminée'])
        ->whereNull('structures.deleted_at')
        ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
        ->when($this->department, function ($query) {
            $query->where('structures.department', $this->department);
        })
        ->when($this->structureId, function ($query) {
            $query->where('structures.id', $this->structureId);
        })
        ->when($this->reseauId, function ($query) {
            $query->where('reseau_structure.reseau_id', $this->reseauId);
        })
        ->groupBy(DB::raw("date_trunc('month', structures.created_at)"));

        // Main query to join date series with structures counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                  date_trunc('month', '$this->startDate'::date),
                  date_trunc('month', '$this->endDate'::date),
                  '1 month'::interval
              ) AS month_start) AS date_series"))
            ->select(
                'date_series.month_start',
                DB::raw("date_part('year', date_series.month_start) as year"),
                DB::raw("date_part('month', date_series.month_start) as month"),
                DB::raw("COALESCE(p.structures_validated, 0) AS structures_validated"),
            )
            ->leftJoinSub($structuresSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->month_start)->format('Y-m-d 00:00:00');
            return $item;
        });
    }

    public function structuresByYear(Request $request)
    {
        $structuresSubquery = DB::table('structures')
        ->select(
            DB::raw("date_trunc('year', structures.created_at) AS created_at"),
            DB::raw("COUNT(*) AS structures_validated"),
        )
        ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
        ->whereIn('structures.state', ['Validée'])
        ->whereNull('structures.deleted_at')
        ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
        ->when($this->department, function ($query) {
            $query->where('structures.department', $this->department);
        })
        ->when($this->structureId, function ($query) {
            $query->where('structures.id', $this->structureId);
        })
        ->when($this->reseauId, function ($query) {
            $query->where('reseau_structure.reseau_id', $this->reseauId);
        })
        ->groupBy(DB::raw("date_trunc('year', structures.created_at)"));

        // Main query to join date series with structures counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                    date_trunc('year', '$this->startDate'::date),
                    date_trunc('year', '$this->endDate'::date),
                    '1 year'::interval
                ) AS year_start) AS date_series"))
            ->select(
                'date_series.year_start',
                DB::raw("date_part('year', date_series.year_start) as year"),
                DB::raw("COALESCE(p.structures_validated, 0) AS structures_validated"),
            )
            ->leftJoinSub($structuresSubquery, 'p', function ($join) {
                $join->on('date_series.year_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.year_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->year_start)->format('Y-m-d 00:00:00');
            return $item;
        });
    }

    public function missionsByMonth(Request $request)
    {
        // Subquery to get counts of missions per month
        $missionsSubquery = DB::table('missions')
        ->select(
            DB::raw("date_trunc('month', missions.created_at) AS created_at"),
            DB::raw("COUNT(*) AS missions_posted"),
        )
        ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
        ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
        ->whereIn('missions.state', ['Validée', 'Terminée'])
        ->whereNull('missions.deleted_at')
        ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
        ->when($this->department, function ($query) {
            $query->where('missions.department', $this->department);
        })
        ->when($this->structureId, function ($query) {
            $query->where('missions.structure_id', $this->structureId);
        })
        ->when($this->reseauId, function ($query) {
            $query->where('reseau_structure.reseau_id', $this->reseauId);
        })
        ->groupBy(DB::raw("date_trunc('month', missions.created_at)"));

        // Main query to join date series with missions counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                 date_trunc('month', '$this->startDate'::date),
                 date_trunc('month', '$this->endDate'::date),
                 '1 month'::interval
             ) AS month_start) AS date_series"))
            ->select(
                'date_series.month_start',
                DB::raw("date_part('year', date_series.month_start) as year"),
                DB::raw("date_part('month', date_series.month_start) as month"),
                DB::raw("COALESCE(p.missions_posted, 0) AS missions_posted"),
            )
            ->leftJoinSub($missionsSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->month_start)->format('Y-m-d 00:00:00');
            return $item;
        });
    }

    public function missionsByYear(Request $request)
    {
        // Subquery to get counts of missions per year
        $missionsSubquery = DB::table('missions')
            ->select(
                DB::raw("date_trunc('year', missions.created_at) AS created_at"),
                DB::raw("COUNT(*) AS missions_posted"),
            )
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->whereIn('missions.state', ['Validée', 'Terminée'])
            ->whereNull('missions.deleted_at')
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->groupBy(DB::raw("date_trunc('year', missions.created_at)"));

        // Main query to join date series with missions counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                    date_trunc('year', '$this->startDate'::date),
                    date_trunc('year', '$this->endDate'::date),
                    '1 year'::interval
                ) AS year_start) AS date_series"))
            ->select(
                'date_series.year_start',
                DB::raw("date_part('year', date_series.year_start) as year"),
                DB::raw("COALESCE(p.missions_posted, 0) AS missions_posted"),
            )
            ->leftJoinSub($missionsSubquery, 'p', function ($join) {
                $join->on('date_series.year_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.year_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->year_start)->format('Y-m-d 00:00:00');
            return $item;
        });
    }

    public function participationsByMonth(Request $request)
    {
        // Subquery to get counts of participations per month
        $participationsSubquery = DB::table('participations')
            ->select(
                DB::raw("date_trunc('month', participations.created_at) AS created_at"),
                DB::raw("COUNT(*) AS participations_total"),
                DB::raw("COUNT(*) FILTER (WHERE participations.state = 'Validée') AS participations_validated")
            )
            ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
            ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('missions.department', $this->department);
            })
            ->when($this->structureId, function ($query) {
                $query->where('missions.structure_id', $this->structureId);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->groupBy(DB::raw("date_trunc('month', participations.created_at)"));

        // Main query to join date series with participation counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                    date_trunc('month', '$this->startDate'::date),
                    date_trunc('month', '$this->endDate'::date),
                    '1 month'::interval
                ) AS month_start) AS date_series"))
            ->select(
                'date_series.month_start',
                DB::raw("date_part('year', date_series.month_start) as year"),
                DB::raw("date_part('month', date_series.month_start) as month"),
                DB::raw("COALESCE(p.participations_total, 0) AS participations_total"),
                DB::raw("COALESCE(p.participations_validated, 0) AS participations_validated"),
                DB::raw("CASE 
                    WHEN COALESCE(p.participations_total, 0) = 0 THEN 0
                    ELSE (COALESCE(p.participations_validated, 0)::float / COALESCE(p.participations_total, 0)::float) * 100
                END AS participations_conversion
                ")
            )
            ->leftJoinSub($participationsSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->month_start)->format('Y-m-d 00:00:00');
            return $item;
        });
    }

    public function participationsByYear(Request $request)
    {
        $participationsSubquery = DB::table('participations')
         ->select(
             DB::raw("date_trunc('year', participations.created_at) AS created_at"),
             DB::raw("COUNT(*) AS participations_total"),
             DB::raw("COUNT(*) FILTER (WHERE participations.state = 'Validée') AS participations_validated")
         )
         ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
         ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
         ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
         ->whereBetween('participations.created_at', [$this->startDate, $this->endDate])
         ->when($this->department, function ($query) {
             $query->where('missions.department', $this->department);
         })
         ->when($this->structureId, function ($query) {
             $query->where('missions.structure_id', $this->structureId);
         })
         ->when($this->reseauId, function ($query) {
             $query->where('reseau_structure.reseau_id', $this->reseauId);
         })
         ->groupBy(DB::raw("date_trunc('year', participations.created_at)"));

        // Main query to join date series with participation counts
        $results = DB::table(DB::raw("(SELECT generate_series(
                    date_trunc('year', '$this->startDate'::date),
                    date_trunc('year', '$this->endDate'::date),
                    '1 year'::interval
                ) AS year_start) AS date_series"))
            ->select(
                'date_series.year_start',
                DB::raw("date_part('year', date_series.year_start) as year"),
                DB::raw("COALESCE(p.participations_total, 0) AS participations_total"),
                DB::raw("COALESCE(p.participations_validated, 0) AS participations_validated"),
                DB::raw("CASE 
                    WHEN COALESCE(p.participations_total, 0) = 0 THEN 0
                    ELSE (COALESCE(p.participations_validated, 0)::float / COALESCE(p.participations_total, 0)::float) * 100
                END AS participations_conversion
                ")
            )
            ->leftJoinSub($participationsSubquery, 'p', function ($join) {
                $join->on('date_series.year_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.year_start', 'DESC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->created_at = Carbon::parse($item->year_start)->format('Y-m-d 00:00:00');
            return $item;
        });

        return $results;
    }

    public function usersByMonth(Request $request)
    {
        // Users department is 20 for Corse
        if ($this->department && in_array($this->department, ['2A', '2B'])) {
            $this->department = '20';
        }

        $results = DB::select(
            "
            SELECT date_trunc('month', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                date_part('month', profiles.created_at) as month,
                count(*) AS profiles_total
            FROM profiles
            WHERE COALESCE(profiles.department,'') ILIKE :department
            GROUP BY date_trunc('month', profiles.created_at), year, month
            ORDER BY date_trunc('month', profiles.created_at) DESC
            ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
            ]
        );

        foreach ($results as $index => $item) {
            if (isset($results[$index + 12])) {
                if($results[$index + 12]->profiles_total) {
                    $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 12]->profiles_total) / $results[$index + 12]->profiles_total) * 100;
                }
            }
        }

        return $results;
    }

    public function usersByYear(Request $request)
    {
        // Users department is 20 for Corse
        if ($this->department && in_array($this->department, ['2A', '2B'])) {
            $this->department = '20';
        }

        $results = DB::select(
            "
            SELECT date_trunc('year', profiles.created_at) AS created_at,
                date_part('year', profiles.created_at) as year,
                count(*) AS profiles_total
            FROM profiles
            WHERE COALESCE(profiles.department,'') ILIKE :department
            GROUP BY date_trunc('year', profiles.created_at), year
            ORDER BY date_trunc('year', profiles.created_at) DESC
            ",
            [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
            ]
        );

        foreach ($results as $index => $item) {
            if (isset($results[$index + 1])) {
                if($results[$index + 1]->profiles_total) {
                    $results[$index]->profiles_total_variation = (($item->profiles_total - $results[$index + 1]->profiles_total) / $results[$index + 1]->profiles_total) * 100;
                }
            }
        }

        return $results;
    }

    public function activityAdminsVsReferents(Request $request)
    {
        $result = ActivityLog::selectRaw("date_trunc('week', activity_log.created_at) AS created_at")
            ->selectRaw("SUM(CASE WHEN roles.id = 1 THEN 1 ELSE 0 END) AS actions_admin_count")
            ->selectRaw("SUM(CASE WHEN roles.id = 3 THEN 1 ELSE 0 END) AS action_referent_count")
            ->leftJoin('users', 'activity_log.causer_id', '=', 'users.id')
            ->leftJoin('rolables', 'rolables.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'rolables.role_id')
            ->leftJoin('missions', function ($join) {
                $join->on('activity_log.subject_id', '=', 'missions.id')
                    ->where('activity_log.subject_type', '=', 'App\Models\Mission');
            })
            ->leftJoin('structures', function ($join) {
                $join->on('activity_log.subject_id', '=', 'structures.id')
                    ->where('activity_log.subject_type', '=', 'App\Models\Structure');
            })
            ->whereIn('roles.id', [1, 3])
            ->where(function ($query) {
                $query->where('activity_log.subject_type', 'App\Models\Structure')
                    ->orWhere('activity_log.subject_type', 'App\Models\Mission');
            })
            ->whereBetween('activity_log.created_at', [$this->startDate, $this->endDate])
            ->where('activity_log.created_at', '>=', now()->subMonths(12))
            ->whereRaw("activity_log.properties->'attributes'->>'state' != ''")
            ->when(
                $this->department,
                function ($query) {
                    return $query->where(function ($query) {
                        $query
                        ->whereHasMorph('subject', Mission::class, function ($query) {
                            return $query->where('department', $this->department);
                        })
                        ->orWhereHasMorph('subject', Structure::class, function ($query) {
                            return $query->where('department', $this->department);
                        });
                    });
                }
            )
            ->when($this->region, function ($query) {
                return $query->where(function ($query) {
                    $query
                    ->whereHasMorph('subject', Mission::class, function ($query) {
                        return $query->whereIn('department', config('taxonomies.regions.departments')[$this->region]);
                    })
                    ->orWhereHasMorph('subject', Structure::class, function ($query) {
                        return $query->whereIn('department', config('taxonomies.regions.departments')[$this->region]);
                    });
                });
            })
            ->groupByRaw("date_trunc('week', activity_log.created_at)")
            ->orderByRaw("date_trunc('week', activity_log.created_at) ASC")
            ->get();


        return $result;
    }
}
