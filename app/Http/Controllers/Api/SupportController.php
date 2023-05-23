<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupportController extends Controller
{
    public $startDate;
    public $endDate;

    public function __construct(Request $request)
    {
        $this->startDate = Carbon::now()->subDays(14);
        $this->endDate = Carbon::now();

        if($request->input('startDate')){
            $this->startDate = Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        }
        if($request->input('endDate')){
            $this->endDate = Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);
        }
    }

    // public function goals(Request $request)
    // {

    //     return [
    //         'utilisateurs_count' => User::whereYear('created_at', '=', date('Y'))->count(),
    //         'organisations_validated_count' => Structure::whereYear('created_at', '=', date('Y'))->where('state', 'Validée')->count(),
    //         'participations_count' => Participation::whereYear('created_at', '=', date('Y'))->count(),
    //         'participations_in_progress_count' => Participation::whereIn('state', ['En attente de validation', 'En cours de traitement', 'Validée'])->whereYear('created_at', '=', date('Y'))->count(),
    //         'participations_validated_count' => Participation::where('state', 'Validée')->whereYear('created_at', '=', date('Y'))->count(),
    //     ];
    // }

    // public function responsables(Request $request)
    // {
    //     $results = DB::select(
    //         "
    //             SELECT missions.responsable_id, COUNT(*) As total_count,
    //             COUNT(*) filter(WHERE participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '10 days')) As waiting_count,
    //             COUNT(*) filter(WHERE participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months')) As in_progress_count
    //             FROM participations
    //             LEFT JOIN missions ON missions.id = participations.mission_id
    //             LEFT JOIN profiles ON profiles.id = missions.responsable_id
    //             WHERE profiles.notification__responsable_frequency = 'realtime'
    //             AND (
    //                 (participations.state = 'En attente de validation' AND participations.created_at < (NOW() - INTERVAL '10 days'))
    //                 OR (participations.state = 'En cours de traitement' AND participations.created_at < (NOW() - INTERVAL '2 months'))
    //             )
    //             AND missions.responsable_id IS NOT NULL
    //             GROUP BY missions.responsable_id
    //             ORDER BY total_count DESC
    //         ", [
    //             'start' => $this->startDate,
    //             'end' => $this->endDate,
    //         ]
    //     );

    //     return $results;
    // }

    public function referents(Request $request)
    {

        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'department_number ASC';
        $searchValue = $request->input('search') ?? null;

        $results = DB::table('profiles')
            ->select(
                'departments.number as department_number',
                'profiles.first_name',
                'profiles.last_name',
                'profiles.email',
                'users.last_online_at',
                DB::raw('COUNT(distinct structures.id) as structures_total_count'),
                DB::raw('COUNT(distinct structures.id) filter(WHERE structures.state = \'En attente de validation\') as structures_waiting_count'),
                DB::raw('COUNT(distinct structures.id) filter(WHERE structures.state = \'En cours de traitement\') as structures_in_progress_count'),
                DB::raw('COUNT(distinct missions.id) as missions_total_count'),
                DB::raw('COUNT(distinct missions.id) filter(WHERE missions.state = \'En attente de validation\') as missions_waiting_count'),
                DB::raw('COUNT(distinct missions.id) filter(WHERE missions.state = \'En cours de traitement\') as missions_in_progress_count')
            )
            ->leftJoin('users', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('rolables', 'rolables.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'rolables.role_id')
            ->leftJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'rolables.rolable_id')
                    ->where('rolables.rolable_type', '=', 'App\Models\Department');
            })
            ->leftJoin('structures', function ($join) {
                $join->on('structures.department', '=', 'departments.number')
                    ->whereIn('structures.state', ['En attente de validation', 'En cours de traitement'])
                    ->whereNull('structures.deleted_at');
            })
            ->leftJoin('missions', function ($join) {
                $join->on('missions.department', '=', 'departments.number')
                    ->whereIn('missions.state', ['En attente de validation', 'En cours de traitement'])
                    ->whereNull('missions.deleted_at');
            })
            ->where('roles.id', 3)
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->groupBy('departments.number', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at')
            ->orderByRaw($orderBy)
            ->get();

        return $results;
    }

    public function referentsInactive(Request $request)
    {
        $results = DB::select(
            "
                SELECT departments.number, departments.name, profiles.first_name, profiles.last_name, profiles.email, profiles.mobile,profiles.phone, users.last_online_at,
                extract('day' from date_trunc('day', now() - users.last_online_at::date)) As inactive_days
                FROM profiles
                LEFT JOIN termables ON termables.termable_id = profiles.id AND termables.termable_type = 'App\Models\Profile'
                LEFT JOIN users ON users.id = profiles.user_id
                LEFT JOIN rolables ON rolables.user_id = users.id
                LEFT JOIN roles ON roles.id = rolables.role_id
                LEFT JOIN departments ON departments.id = rolables.rolable_id AND rolables.rolable_type = 'App\Models\Department'
                WHERE roles.id = 3
                AND users.last_online_at <= NOW() - interval '1 month'
                AND termables.term_id = 652
                ORDER BY departments.number ASC
            "
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
            ", [
                'start' => $this->startDate,
                'end' => $this->endDate,
            ]
        );

        return $results;
    }

}
