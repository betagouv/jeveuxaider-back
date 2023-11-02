<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

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

    public function referentsOverview(Request $request)
    {
        return [
            'referents_online_count' => User::role('referent')->online()->count(),
            'referents_count' => User::role('referent')->count(),
            'referents_inactive_count' => User::role('referent')->inactive()->count(),
            'structures_need_to_be_treated_count' => Structure::whereIn('state', ['En attente de validation', 'En cours de traitement'])->count(),
            'structures_waiting_count' => Structure::whereIn('state', ['En attente de validation'])->count(),
            'structures_in_progress_count' => Structure::whereIn('state', ['En cours de traitement'])->count(),
            'missions_need_to_be_treated_count' => Mission::whereIn('state', ['En attente de validation', 'En cours de traitement'])->count(),
            'missions_waiting_count' => Mission::whereIn('state', ['En attente de validation'])->count(),
            'missions_in_progress_count' =>Mission::whereIn('state', ['En cours de traitement'])->count(),
        ];
    }

    public function responsablesOverview(Request $request)
    {
        return [
            'responsables_online_count' => User::role('responsable')->online()->count(),
            'responsables_count' => User::role('responsable')->count(),
            'responsables_inactive_count' => User::role('responsable')->inactive()->count(),
            'participations_need_to_be_treated_count' => Participation::needToBeTreated()->count(),
            'participations_waiting_count' => Participation::needToBeTreated()->whereIn('state', ['En attente de validation'])->count(),
            'participations_in_progress_count' => Participation::needToBeTreated()->whereIn('state', ['En cours de traitement'])->count(),
            'missions_outdated_count' => Mission::whereIn('state', ['En attente de validation', 'En cours de traitement'])->count(),
        ];
    }

    public function referentsWaitingActions(Request $request)
    {

        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'missions_total_count DESC';
        $searchValue = $request->input('search') ?? null;
        $departmentValue = $request->input('department') ?? null;
        $tagValue = $request->input('tag') ?? null;
        $inactiveValue = $request->input('inactive') ?? null;
        $onlineValue = $request->input('online') ?? null;

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
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->join('rolables', 'rolables.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'rolables.role_id')
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
                $query->where("users.last_online_at", "<=" , Carbon::now()->subMonth(1));
            })
            ->when($onlineValue, function($query) {
                $query->where("users.last_online_at", ">=" , Carbon::now()->subMinutes(10));
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
        $inactiveValue = $request->input('inactive') ?? null;
        $onlineValue = $request->input('online') ?? null;

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
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->join('rolables', 'rolables.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'rolables.role_id')
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
            ->when($inactiveValue, function($query) {
                $query->where("users.last_online_at", "<=" , Carbon::now()->subMonth(1));
            })
            ->when($onlineValue, function($query) {
                $query->where("users.last_online_at", ">=" , Carbon::now()->subMinutes(10));
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
        $onlineValue = $request->input('online') ?? null;

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
                    ->whereNull('structures.deleted_at');
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
                $query->where(function($query){
                    $query->where('participations.state', 'En attente de validation')
                        ->where('participations.created_at', '<', Carbon::now()->subDays(7)->startOfDay());
                })
                ->orWhere(function($query){
                    $query
                        ->where('participations.state', 'En cours de traitement')
                        ->where('participations.created_at', '<', Carbon::now()->subMonths(2)->startOfDay());
                });
            })
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->when($inactiveValue, function($query) {
                $query->where("users.last_online_at", "<=" , Carbon::now()->subMonth(1));
            })
            ->when($onlineValue, function($query) {
                $query->where("users.last_online_at", ">=" , Carbon::now()->subMinutes(10));
            })
            ->when($organisationValue, function($query) use ($organisationValue){
                $query->where('structures.id', '=', $organisationValue);
            })
            ->groupBy('profiles.id', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at', 'structures.name', 'structures.id')
            ->orderByRaw($orderBy)
            ->paginate(20);

        return $results;
    }

    public function responsablesMissionsOutdated(Request $request)
    {
        $orderBy = $request->input('sort') ? $request->input('sort') . ' DESC' : 'missions_total_count DESC';
        $searchValue = $request->input('search') ?? null;
        $inactiveValue = $request->input('inactive') ?? null;
        $organisationValue = $request->input('organisation') ?? null;
        $onlineValue = $request->input('online') ?? null;

        $results = DB::table('profiles')
            ->select(
                'profiles.id as profile_id',
                'profiles.first_name',
                'profiles.last_name',
                'profiles.email',
                'users.last_online_at',
                'structures.name as structure_name',
                'structures.id as structure_id',
                DB::raw('COUNT(distinct missions.id) as missions_total_count'),
            )
            ->join('users', 'users.id', '=', 'profiles.user_id')
            ->join('rolables', 'rolables.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'rolables.role_id')
            ->join('structures', function ($join) use ($organisationValue) {
                $join->on('rolables.rolable_id', '=', 'structures.id')
                    ->where('rolables.rolable_type', 'App\Models\Structure')
                    ->whereIn('structures.state', ['Validée'])
                    ->whereNull('structures.deleted_at');
            })
            ->join('missions', function ($join) {
                $join->on('missions.responsable_id', '=', 'profiles.id')
                    ->whereIn('missions.state', ['Validée'])
                    ->where('missions.end_date', '<', Carbon::now())
                    ->whereNull('missions.deleted_at');
            })
            ->where('roles.id', 2)
            ->when($searchValue, function($query) use ($searchValue){
                $query->whereRaw("CONCAT(profiles.first_name, ' ', profiles.last_name, ' ', profiles.email) ILIKE ?", ['%' . $searchValue . '%']);
            })
            ->when($inactiveValue, function($query) {
                $query->where("users.last_online_at", "<=" , Carbon::now()->subMonth(1));
            })
            ->when($onlineValue, function($query) {
                $query->where("users.last_online_at", ">=" , Carbon::now()->subMinutes(10));
            })
            ->when($organisationValue, function($query) use ($organisationValue){
                $query->where('structures.id', '=', $organisationValue);
            })
            ->groupBy('profiles.id', 'profiles.first_name', 'profiles.last_name', 'profiles.email', 'users.last_online_at', 'structures.name', 'structures.id')
            ->orderByRaw($orderBy)
            ->paginate(20);

        return $results;

    }

    public function generatePasswordResetLink(Request $request)
    {
        $user = User::find($request->input('user_id'));

        if (!$user) {
            abort('422', "L'utilisateur n'a pas été trouvé !");
        }

        $token = Password::createToken($user);
        return config('app.front_url') . '/password-reset/' . $token . '?email=' . urlencode($user->email);
    }

}
