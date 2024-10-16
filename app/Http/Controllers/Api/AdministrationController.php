<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdministrationController extends Controller
{
    public $startDate;
    public $endDate;

    public function __construct(Request $request)
    {
        $this->startDate = Carbon::now()->subDays(14);
        $this->endDate = Carbon::now();

        if($request->input('start_date')) {
            $this->startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->hour(0)->minute(0)->second(0);
        }
        if($request->input('end_date')) {
            $this->endDate = Carbon::createFromFormat('Y-m-d', $request->input('end_date'))->hour(23)->minute(59)->second(59);
        }
    }

    public function goals(Request $request)
    {

        return [
            'utilisateurs_count' => User::whereYear('created_at', '=', date('Y'))->count(),
            'organisations_validated_count' => Structure::whereYear('created_at', '=', date('Y'))->where('state', 'Validée')->count(),
            'participations_count' => Participation::whereYear('created_at', '=', date('Y'))->count(),
            'participations_in_progress_count' => Participation::whereIn('state', ['En attente de validation', 'En cours de traitement', 'Validée'])->whereYear('created_at', '=', date('Y'))->count(),
            'participations_validated_count' => Participation::where('state', 'Validée')->whereYear('created_at', '=', date('Y'))->count(),
        ];
    }

    public function topitoAdmins(Request $request)
    {
        $results = DB::select(
            "
                SELECT activity_log.causer_id, profiles.id as profile_id, profiles.first_name, profiles.last_name, COUNT(*) AS count FROM activity_log
                LEFT JOIN users ON users.id = activity_log.causer_id
                LEFT JOIN profiles ON users.id = profiles.user_id
                LEFT JOIN rolables ON rolables.user_id = users.id
                WHERE activity_log.causer_type = 'App\Models\User'
                AND rolables.role_id = 1
                AND activity_log.created_at BETWEEN :start and :end
                GROUP BY activity_log.causer_id,  profiles.id, profiles.first_name, profiles.last_name
                ORDER BY count DESC
                LIMIT 5
            ",
            [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function topitoReferents(Request $request)
    {
        $results = DB::select(
            "
                SELECT activity_log.causer_id, profiles.id as profile_id, profiles.first_name, profiles.last_name, COUNT(*) AS count FROM activity_log
                LEFT JOIN users ON users.id = activity_log.causer_id
                LEFT JOIN profiles ON users.id = profiles.user_id
                LEFT JOIN rolables ON rolables.user_id = users.id
                WHERE activity_log.causer_type = 'App\Models\User'
                AND rolables.role_id = 3
                AND activity_log.created_at BETWEEN :start and :end
                GROUP BY activity_log.causer_id, profiles.id, profiles.first_name, profiles.last_name
                ORDER BY count DESC
                LIMIT 5
            ",
            [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }


    public function missionsTrending(Request $request)
    {
        $results = DB::select(
            "
                SELECT missions.id, missions.name, structures.name as structure_name, mission_templates.title as template_name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY missions.id, missions.name, structures.name, mission_templates.title
                ORDER BY count DESC
                LIMIT 5
            ",
            [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

    public function organisationsTrending(Request $request)
    {
        $results = DB::select(
            "
                SELECT structures.id, structures.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN structures ON structures.id = missions.structure_id
                WHERE participations.deleted_at IS NULL
                AND missions.deleted_at IS NULL
                AND participations.created_at BETWEEN :start and :end
                GROUP BY structures.id, structures.name
                ORDER BY count DESC
                LIMIT 5
            ",
            [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }
}
