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

    public function referentsWaitingActions(Request $request)
    {

        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'missions_total_count DESC';
        $searchValue = $request->input('search') ?? null;
        $departmentValue = $request->input('department') ?? null;
        $tagValue = $request->input('tag') ?? null;
        $inactiveValue = $request->input('inactive') ?? null;

        $results = DB::table('profiles')
            ->select(
                'departments.number as department_number',
                'profiles.id as profile_id',
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
            ->when($tagValue, function($query) use ($tagValue){
                $query->join('termables', function ($join) use ($tagValue) {
                    $join->on('termables.termable_id', '=', 'profiles.id')
                        ->where('termables.term_id', $tagValue)
                        ->where('termables.termable_type', '=', 'App\Models\Profile');
                });
            })
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->when($inactiveValue, function($query) {
                $query->whereRaw("users.last_online_at <= NOW() - interval '1 month'");
            })
            ->when($departmentValue, function($query) use ($departmentValue){
                $query->where('departments.number', $departmentValue);
            })
            ->groupBy('departments.number','profiles.id', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at')
            ->orderByRaw($orderBy)
            ->paginate(20);

        return $results;
    }

    public function referentsActivityLogs(Request $request)
    {
        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'activity_logs_last_month_count DESC';
        $searchValue = $request->input('search') ?? null;
        $departmentValue = $request->input('department') ?? null;

        $results = DB::table('profiles')
            ->select(
                'departments.number as department_number',
                'profiles.id as profile_id',
                'profiles.first_name',
                'profiles.last_name',
                'profiles.email',
                'users.last_online_at',
                DB::raw('COUNT(distinct activity_log.id) as activity_logs_total_count'),
                DB::raw('COUNT(distinct activity_log.id) filter(WHERE activity_log.created_at >= NOW() - interval \'1 month\') as activity_logs_last_month_count'),
                DB::raw('COUNT(distinct activity_log.id) filter(WHERE activity_log.created_at >= NOW() - interval \'1 week\') as activity_logs_last_week_count'),
            )
            ->leftJoin('users', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('rolables', 'rolables.user_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'rolables.role_id')
            ->leftJoin('departments', function ($join) {
                $join->on('departments.id', '=', 'rolables.rolable_id')
                    ->where('rolables.rolable_type', '=', 'App\Models\Department');
            })
            ->leftJoin('activity_log', function ($join) {
                $join->on('activity_log.causer_id', '=', 'users.id')
                    ->where('activity_log.causer_type', '=', 'App\Models\User');
            })
            ->where('roles.id', 3)
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->when($departmentValue, function($query) use ($departmentValue){
                $query->where('departments.number', $departmentValue);
            })
            ->groupBy('departments.number','profiles.id', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at')
            ->orderByRaw($orderBy)
            ->paginate(20);

        return $results;
    }

    public function responsablesParticipationsToBeTreated(Request $request)
    {

        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'participations_total_count DESC';
        $searchValue = $request->input('search') ?? null;
        $inactiveValue = $request->input('inactive') ?? null;
        $organisationValue = $request->input('organisation') ?? null;

        $results = DB::table('profiles')
            ->select(
                'profiles.id as profile_id',
                'profiles.first_name',
                'profiles.last_name',
                'profiles.email',
                'users.last_online_at',
                'structures.name as structure_name',
                'structures.id as structure_id',
                DB::raw('COUNT(distinct participations.id) as participations_total_count'),
                DB::raw('COUNT(distinct participations.id) filter(WHERE participations.state = \'En attente de validation\') as participations_waiting_count'),
                DB::raw('COUNT(distinct participations.id) filter(WHERE participations.state = \'En cours de traitement\') as participations_in_progress_count'),
            )
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->join('rolables', 'rolables.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'rolables.role_id')
            ->join('structures', function ($join) use ($organisationValue) {
                $join->on('rolables.rolable_id', '=', 'structures.id')
                    ->where('rolables.rolable_type', 'App\Models\Structure')
                    ->whereIn('structures.state', ['Validée'])
                    ->whereNull('structures.deleted_at')
                    ->when($organisationValue, function($query) use ($organisationValue){
                        $query->where('structures.id', '=', $organisationValue);
                    });
            })
            ->join('missions', function ($join) {
                $join->on('missions.responsable_id', '=', 'profiles.id')
                    ->whereIn('missions.state', ['Validée', 'Terminée'])
                    ->whereNull('missions.deleted_at');
            })
            ->join('participations', function ($join) {
                $join->on('participations.mission_id', '=', 'missions.id')
                    ->whereIn('participations.state', ['En attente de validation', 'En cours de traitement']);
            })
            ->where('roles.id', 2)
            ->where(function($query){
                $query
                    ->where('participations.state', 'En attente de validation')
                    ->where('participations.created_at', '<', Carbon::now()->subDays(10)->startOfDay());
                })
                ->orWhere(function($query){
                    $query
                        ->where('participations.state', 'En cours de traitement')
                        ->where('participations.created_at', '<', Carbon::now()->subMonths(2)->startOfDay());
            })
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->when($inactiveValue, function($query) {
                $query->whereRaw("users.last_online_at <= NOW() - interval '1 month'");
            })
            ->groupBy('profiles.id', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at', 'structures.name', 'structures.id')
            ->orderByRaw($orderBy)
            ->paginate(20);

        return $results;
    }

}
