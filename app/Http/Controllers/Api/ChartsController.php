<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public $startDate;

    public $endDate;

    public $department;

    public function __construct(Request $request)
    {
        $this->startYear = 2020;
        $this->endYear = date('Y');

        if ($request->input('period') == 'current_year') {
            $this->startDate = Carbon::now()->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_year') {
            $this->startDate = Carbon::now()->subYear(1)->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subYear(1)->endOfYear()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'current_month') {
            $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_month') {
            $this->startDate = Carbon::now()->subMonth(1)->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subMonth(1)->endOfMonth()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'current_week') {
            $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d H:i:s');
        } elseif ($request->input('period') == 'last_week') {
            $this->startDate = Carbon::now()->subWeek(1)->startOfWeek()->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->subWeek(1)->endOfWeek()->format('Y-m-d H:i:s');
        } else {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }

        if ($request->has('department')) {
            $this->department = $request->input('department');
        }
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
                WHERE structures.department ILIKE :department
                AND structures.state IN ('Validée')
                AND structures.deleted_at IS NULL
                GROUP BY date_trunc('month', structures.created_at), year, month
                ORDER BY date_trunc('month', structures.created_at) ASC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
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

    public function missionsByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT date_trunc('month', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                date_part('month', missions.created_at) as month,
                count(*) AS count
                FROM missions
                WHERE missions.department ILIKE :department
                AND missions.state IN ('Validée', 'Terminée')
                GROUP BY date_trunc('month', missions.created_at), year, month
                ORDER BY date_trunc('month', missions.created_at) ASC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
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
                WHERE missions.department ILIKE :department
                GROUP BY date_trunc('month', participations.created_at), year, month
                ORDER BY date_trunc('month', participations.created_at) ASC
            ", [
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
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

    public function participationsConversionByDate(Request $request)
    {
        $items = [];

        $results = DB::select("
                SELECT
                date_part('year', participations.created_at) as year,
                date_part('month', participations.created_at) as month,
                sum(case when participations.state  = 'Validée' then 1 else 0 end) as participations_validated_count,
                sum(case when participations.state  != 'Validée' then 1 else 0 end) as participations_others_count,
                count(*) AS count
                FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE participations.created_at BETWEEN :start and :end
                AND missions.department ILIKE :department
                GROUP BY date_trunc('month', participations.created_at), YEAR, MONTH
                ORDER BY date_trunc('month', participations.created_at) ASC
            ", [
            'start' => $this->startDate,
            'end' => $this->endDate,
            'department' => $this->department ? '%'.$this->department.'%' : '%%',
        ]);

        $collection = collect($results);

        for ($month = 1; $month < 13; $month++) {
            $item = $collection->where('month', $month)->first();
            $items['Validées'][] = $item ? $item->participations_validated_count : 0;
            $items['Autres statuts'][] = $item ? $item->participations_others_count : 0;
        }

        return $items;
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
                WHERE profiles.department ILIKE :department
                GROUP BY date_trunc('month', profiles.created_at), year, month
                ORDER BY date_trunc('month', profiles.created_at) ASC
            ", [
            'department' => $this->department ? $this->department.'%' : '%%',
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
}
