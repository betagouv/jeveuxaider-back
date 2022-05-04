<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\MissionTemplate;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Reseau;
use Carbon\Carbon;
use App\Models\Structure;
use App\Models\Territoire;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class NumbersController extends Controller
{
    public $year;
    public $month;
    public $date;
    public $startDate;
    public $endDate;

    public function __construct(Request $request)
    {
        if ($request->input('period') == 'all') {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'year') {
            $this->date = Carbon::parse($request->input('year')."-01-01");
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'month') {
            $this->date = Carbon::parse($request->input('year')."-".$request->input('month')."-01");
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        } else {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    public function global(Request $request)
    {

        $missionsAvailable = Mission::role($request->header('Context-Role'))
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->available()
        ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
        'organisations' => Structure::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
        'participations' => Participation::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'participations_validated' => Participation::whereBetween('created_at', [$this->startDate, $this->endDate])->where('state', 'Validée')->count(),
        'places_left' => $placesLeft,
        'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        'users' => Profile::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'users_benevoles' => Profile::whereBetween('created_at', [$this->startDate, $this->endDate])->benevole()->count(),
        'reseaux' => Reseau::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'reseaux_actives' => Reseau::whereBetween('created_at', [$this->startDate, $this->endDate])->where('is_published', true)->count(),
        'territoires' => Territoire::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'territoires_actives' => Territoire::whereBetween('created_at', [$this->startDate, $this->endDate])->where('is_published', true)->count(),
        'mission_templates' => MissionTemplate::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'mission_templates_actives' => MissionTemplate::whereBetween('created_at', [$this->startDate, $this->endDate])->where('published', true)->count(),
        'activities' => Activity::whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'activities_actives' => Activity::whereBetween('created_at', [$this->startDate, $this->endDate])->where('is_published', true)->count(),
        ];
    }

    public function globalOrganisations(Request $request)
    {

        $missionsAvailable = Mission::with('structure')->role($request->header('Context-Role'))
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->available()
        ->get();

        return [
        'associations' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Association')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'associations_actives' => $missionsAvailable->where('structure.statut_juridique', 'Association')->pluck('structure_id')->unique()->count(),
        'collectivites' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Collectivité')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'collectivites_actives' => $missionsAvailable->where('structure.statut_juridique', 'Collectivité')->pluck('structure_id')->unique()->count(),
        'organisations_publiques' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Organisation publique')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'organisations_publiques_actives' => $missionsAvailable->where('structure.statut_juridique', 'Organisation publique')->pluck('structure_id')->unique()->count(),
        'organisations_privees' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Organisation privée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'organisations_privees_actives' => $missionsAvailable->where('structure.statut_juridique', 'Organisation privée')->pluck('structure_id')->unique()->count(),
        ];
    }

    public function organisationsByStates(Request $request)
    {
        return [
        'draft' => Structure::role($request->header('Context-Role'))->where('state', 'Brouillon')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'waiting' => Structure::role($request->header('Context-Role'))->where('state', 'En attente de validation')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'in_progress' => Structure::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'validated' => Structure::role($request->header('Context-Role'))->where('state', 'Validée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'signaled' => Structure::role($request->header('Context-Role'))->where('state', 'Signalée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'unsubscribed' => Structure::role($request->header('Context-Role'))->where('state', 'Désinscrite')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
    }

    public function organisationsByTypes(Request $request)
    {
        return [
        'associations' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Association')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'collectivites' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Collectivité')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'organisations_publiques' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Organisation publique')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'organisations_privees' => Structure::role($request->header('Context-Role'))->where('statut_juridique', 'Organisation privée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function organisationsByReseaux(Request $request)
    {

        $results = DB::select("
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count FROM structures
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND reseaux.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByReseaux(Request $request)
    {

        $results = DB::select("
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND reseaux.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByReseaux(Request $request)
    {

        $results = DB::select("
                SELECT reseaux.name, reseaux.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN reseau_structure ON reseau_structure.structure_id = structures.id
                LEFT JOIN reseaux ON reseaux.id = reseau_structure.reseau_id
                WHERE structures.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND reseaux.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY reseaux.name, reseaux.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }


    public function globalMissions(Request $request)
    {
        return [
        'missions' => Mission::role($request->header('Context-Role'))->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'missions_actives' => Mission::role($request->header('Context-Role'))->available()->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'missions_libres' => Mission::role($request->header('Context-Role'))->whereNull('template_id')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'missions_libres_actives' => Mission::role($request->header('Context-Role'))->whereNull('template_id')->available()->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'missions_templates' => Mission::role($request->header('Context-Role'))->whereNotNull('template_id')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'missions_templates_actives' => Mission::role($request->header('Context-Role'))->whereNotNull('template_id')->available()->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
    }

    public function globalParticipations(Request $request)
    {
        return [
        'participations' => Participation::role($request->header('Context-Role'))->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'participations_actives' => Participation::role($request->header('Context-Role'))->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
    }

    public function participationsByStates(Request $request)
    {
        return [
        'waiting' => Participation::role($request->header('Context-Role'))->where('state', 'En attente de validation')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'in_progress' => Participation::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'refused' => Participation::role($request->header('Context-Role'))->where('state', 'Refusée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'canceled' => Participation::role($request->header('Context-Role'))->where('state', 'Annulée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
    }

    public function missionsByStates(Request $request)
    {
        return [
        'draft' => Mission::role($request->header('Context-Role'))->where('state', 'Brouillon')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'waiting' => Mission::role($request->header('Context-Role'))->where('state', 'En attente de validation')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'in_progress' => Mission::role($request->header('Context-Role'))->where('state', 'En cours de traitement')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'validated' => Mission::role($request->header('Context-Role'))->where('state', 'Validée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'finished' => Mission::role($request->header('Context-Role'))->where('state', 'Terminée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'canceled' => Mission::role($request->header('Context-Role'))->where('state', 'Annulée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'signaled' => Mission::role($request->header('Context-Role'))->where('state', 'Signalée')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
    }

    public function missionsByTypes(Request $request)
    {
        return [
        'presentiels' => Mission::role($request->header('Context-Role'))->where('type', 'Mission à distance')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        'distances' => Mission::role($request->header('Context-Role'))->where('type', 'Mission en présentiel')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
        ];
    }

    public function missionsByOrganisations(Request $request)
    {

        $results = DB::select("
                SELECT structures.name, structures.id, COUNT(*) AS count FROM missions
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE missions.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND structures.name IS NOT NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY structures.name, structures.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function offers(Request $request)
    {

        $missionsAvailable = Mission::role($request->header('Context-Role'))
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->available()
        ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        return [
        'missions' => Mission::count(),
        'missions_actives' => $missionsAvailable->count(),
        'places' => $placesOffered,
        'places_left' => $placesLeft,
        'places_occupation_rate' => $placesOffered ? round((($placesOffered - $placesLeft) / $placesOffered) * 100) : 0,
        ];
    }

    public function participationsByActivities(Request $request)
    {

        $results = DB::select("
                SELECT activities.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                AND activities.name IS NOT NULL
                GROUP BY activities.name
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByMissionTemplates(Request $request)
    {

        $results = DB::select("
                SELECT mission_templates.title, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND mission_templates.title IS NOT NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY mission_templates.title
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByDepartments(Request $request)
    {

        $results = DB::select("
                SELECT territoires.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN territoires ON territoires.department = missions.department
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND territoires.name IS NOT NULL
                AND territoires.type = 'department'
                AND participations.created_at BETWEEN :start and :end
                GROUP BY territoires.name
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByMissions(Request $request)
    {

        $results = DB::select("
                SELECT missions.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY missions.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByOrganisations(Request $request)
    {

        $results = DB::select("
                SELECT structures.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND structures.name IS NOT NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY structures.name
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsByDomaines(Request $request)
    {

        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
                LIMIT 10
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByActivities(Request $request)
    {

        $results = DB::select("
                SELECT activities.name, activities.id, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE missions.deleted_at IS NULL
                AND missions.created_at BETWEEN :start and :end
                AND activities.name IS NOT NULL
                GROUP BY activities.name, activities.id
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByDomaines(Request $request)
    {

        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN domaines ON domaines.id = mission_templates.domaine_id OR domaines.id = missions.domaine_id OR domaines.id = missions.domaine_secondary_id
                WHERE missions.deleted_at IS NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
                LIMIT 10
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByTemplates(Request $request)
    {

        $results = DB::select("
                SELECT mission_templates.title, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE missions.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND mission_templates.title IS NOT NULL
                AND missions.created_at BETWEEN :start and :end
                GROUP BY mission_templates.title
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsByDepartments(Request $request)
    {

        $results = DB::select("
                SELECT territoires.name, COUNT(*) AS count FROM missions
                LEFT JOIN territoires ON territoires.department = missions.department
                WHERE missions.deleted_at IS NULL
                AND territoires.name IS NOT NULL
                AND territoires.type = 'department'
                AND missions.created_at BETWEEN :start and :end
                GROUP BY territoires.name
                ORDER BY count DESC
                LIMIT 5
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function organisationsByDomaines(Request $request)
    {

        $results = DB::select("
                SELECT domaines.name, domaines.id, COUNT(*) AS count FROM structures
                LEFT JOIN domainables ON domainables.domainable_id = structures.id AND domainables.domainable_type = 'App\Models\Structure'
                LEFT JOIN domaines ON domaines.id = domainables.domaine_id
                WHERE structures.deleted_at IS NULL
                AND domaines.name IS NOT NULL
                AND structures.created_at BETWEEN :start and :end
                GROUP BY domaines.name, domaines.id
                ORDER BY count DESC
                LIMIT 10
            ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function missionsOutdatedByOrganisations(Request $request)
    {
        $results = DB::table('missions')
            ->select(['structures.name','structures.id'])
            ->selectRaw('count(*) as count')
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->groupBy(['structures.name','structures.id'])
            ->whereNotNull('missions.end_date')
            ->where('missions.end_date', '>=', date("Y-m-d"))
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->orderByDesc('count')
            ->limit(100)
            ->get();

        return $results;
    }

    public function participationsWaitingByOrganisations(Request $request)
    {

        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En attente de validation'
            AND missions.deleted_at IS NULL
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function participationsInProgressByOrganisations(Request $request)
    {

        $results = DB::select("
            SELECT structures.name, structures.id, COUNT(*) AS count FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.deleted_at IS NULL
            AND participations.state = 'En cours de traitement'
            AND missions.deleted_at IS NULL
            AND structures.deleted_at IS NULL
            AND structures.name IS NOT NULL
            AND participations.created_at BETWEEN :start and :end
            GROUP BY structures.name, structures.id
            ORDER BY count DESC
            LIMIT 100
        ", [
            "start" => $this->startDate,
            "end" => $this->endDate,
        ]);

        return $results;
    }

    public function organisationsWaitingByDepartments(Request $request)
    {

        $results = DB::table('structures')
            ->select(['territoires.id','territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id','territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function organisationsInProgressByDepartments(Request $request)
    {

        $results = DB::table('structures')
            ->select(['territoires.id','territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'structures.department')
            ->whereNull('structures.deleted_at')
            ->whereNotNull('structures.department')
            ->where('structures.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id','territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsWaitingByDepartments(Request $request)
    {

        $results = DB::table('missions')
            ->select(['territoires.id','territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En attente de validation')
            ->where('territoires.type', 'department')
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id','territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsInProgressByDepartments(Request $request)
    {

        $results = DB::table('missions')
            ->select(['territoires.id','territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->where('missions.state', 'En cours de traitement')
            ->where('territoires.type', 'department')
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id','territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }

    public function missionsOutdatedByDepartments(Request $request)
    {

        $results = DB::table('missions')
            ->select(['territoires.id','territoires.department'])
            ->selectRaw('count(*) as count')
            ->leftJoin('territoires', 'territoires.department', '=', 'missions.department')
            ->whereNull('missions.deleted_at')
            ->whereNotNull('missions.department')
            ->whereNotNull('missions.end_date')
            ->where('territoires.type', 'department')
            ->where('missions.end_date', '>=', date("Y-m-d"))
            ->whereBetween('missions.created_at', [$this->startDate, $this->endDate])
            ->whereNotNull('territoires.department')
            ->groupBy(['territoires.id','territoires.department'])
            ->orderByDesc('count')
            ->get();

        return $results;
    }
}
