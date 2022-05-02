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
        }
        elseif ($request->input('period') == 'year') {
            $this->date = Carbon::parse($request->input('year')."-01-01");
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        elseif ($request->input('period') == 'month') {
            $this->date = Carbon::parse($request->input('year')."-".$request->input('month')."-01");
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        else {
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
            'unsuscribed' => Structure::role($request->header('Context-Role'))->where('state', 'Désinscrite')->whereBetween('created_at', [$this->startDate, $this->endDate])->count(),
         ];
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

    public function trendsParticipationsByActivities(Request $request)
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

    public function trendsParticipationsByMissionTemplates(Request $request)
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

    public function trendsParticipationsByDepartments(Request $request)
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

    public function trendsParticipationsByMissions(Request $request)
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

    public function trendsParticipationsByOrganisations(Request $request)
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

    public function trendsParticipationsByReseaux(Request $request)
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

    public function trendsMissionsByActivities(Request $request)
    {

            $results = DB::select("
                SELECT activities.name, COUNT(*) AS count FROM missions
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id OR activities.id = missions.activity_id
                WHERE missions.deleted_at IS NULL
                AND missions.created_at BETWEEN :start and :end
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

    public function trendsMissionsByMissionTemplates(Request $request)
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

    public function trendsMissionsByDepartments(Request $request)
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
}
