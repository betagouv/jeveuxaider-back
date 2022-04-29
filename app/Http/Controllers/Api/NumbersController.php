<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use Carbon\Carbon;
use App\Models\Structure;
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

        if ($request->input('period') == 'last-30-days') {
            $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d H:i:s');
            $this->endDate =  Carbon::now()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'last-7-days') {
            $this->startDate = Carbon::now()->subDays(7)->format('Y-m-d H:i:s');
            $this->endDate =  Carbon::now()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'current-month') {
            $this->date = Carbon::now();
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'last-month') {
            $this->date = Carbon::now()->subMonth(1);
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'current-year') {
            $this->year = date('Y');
            $this->date = Carbon::parse($this->year."-01-01");
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'last-year') {
            $this->year = date('Y') - 1;
            $this->date = Carbon::parse($this->year."-01-01");
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        if ($request->input('period') == 'all') {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }

        ray($this->startDate);
        ray($this->endDate);
    }

    public function global(Request $request)
    {

        $currentPeriodBetween = [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth()];

        return [
            'participations' => [
                'total' => Participation::role($request->header('Context-Role'))->count(),
                'current_period' => Participation::role($request->header('Context-Role'))->whereBetween(
                    'created_at',
                    $currentPeriodBetween
                )->count(),
            ],
            'missions' => [
                'total' => Mission::role($request->header('Context-Role'))->count(),
                'current_period' => Mission::role($request->header('Context-Role'))->whereBetween(
                    'created_at',
                    $currentPeriodBetween
                )->count(),
            ],
            'organisations' => [
                'total' => Structure::role($request->header('Context-Role'))->count(),
                'current_period' => Structure::role($request->header('Context-Role'))->whereBetween(
                    'created_at',
                    $currentPeriodBetween
                )->count(),
            ],
            'users' => [
                'total' => Profile::role($request->header('Context-Role'))->count(),
                'current_period' => Profile::role($request->header('Context-Role'))->whereBetween(
                    'created_at',
                    $currentPeriodBetween
                )->count(),
            ],
        ];
    }

    public function trendsParticipationsByActivities(Request $request)
    {

            $results = DB::select("
                SELECT activities.name, COUNT(*) AS count FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                LEFT JOIN mission_templates ON mission_templates.id = missions.template_id
                LEFT JOIN activities ON activities.id = mission_templates.activity_id
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

        ray($results);

        return $results;
    }
}
