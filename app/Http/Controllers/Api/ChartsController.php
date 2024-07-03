<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChartsController extends Controller
{
    public $startDate;
    public $endDate;
    public $startYear;
    public $endYear;
    public $department;

    public function __construct(Request $request)
    {
        $this->startYear = 2020;
        $this->endYear = date('Y');

        if($request->input('startDate')) {
            $this->startDate =  Carbon::createFromFormat('Y-m-d', $request->input('startDate'))->hour(0)->minute(0)->second(0);
        }
        if($request->input('endDate')) {
            $this->endDate =  Carbon::createFromFormat('Y-m-d', $request->input('endDate'))->hour(23)->minute(59)->second(59);
        }
        if($request->header('Context-Role') == 'referent') {
            $this->department = Auth::guard('api')->user()->departmentsAsReferent->first()->number;
        } elseif($request->input('department')) {
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

    public function missionsByDate(Request $request)
    {
        $items = [];

        if($request->header('Context-Role') === 'responsable') {
            $results = DB::select("
                SELECT date_trunc('month', missions.created_at) AS created_at,
                date_part('year', missions.created_at) as year,
                date_part('month', missions.created_at) as month,
                count(*) AS count
                FROM missions
                WHERE COALESCE(missions.department,'') ILIKE :department
                AND missions.structure_id = :structureId
                AND missions.state IN ('Validée', 'Terminée')
                GROUP BY date_trunc('month', missions.created_at), year, month
                ORDER BY date_trunc('month', missions.created_at) ASC
            ", [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'structureId' => Auth::user()->contextable_id
            ]);
        } else {
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
        }

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

        if($request->header('Context-Role') === 'responsable') {
            $results = DB::select("
                SELECT date_trunc('month', participations.created_at) AS created_at,
                date_part('year', participations.created_at) as year,
                date_part('month', participations.created_at) as month,
                count(*) AS count
                FROM participations
                LEFT JOIN missions ON missions.id = participations.mission_id
                WHERE COALESCE(missions.department,'') ILIKE :department
                AND missions.structure_id = :structureId
                GROUP BY date_trunc('month', participations.created_at), year, month
                ORDER BY date_trunc('month', participations.created_at) ASC
            ", [
                'department' => $this->department ? '%' . $this->department . '%' : '%%',
                'structureId' => Auth::user()->contextable_id
            ]);
        } else {
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
        }

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
                WHERE COALESCE(missions.department,'') ILIKE :department
                GROUP BY date_trunc('month', participations.created_at), YEAR, MONTH
                ORDER BY date_trunc('month', participations.created_at) ASC
            ", [
            'department' => $this->department ? '%' . $this->department . '%' : '%%',
        ]);

        $collection = collect($results);

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $item = $collection->where('year', $year)->where('month', $month)->first();
                $items[$year . ' - A - Validated'][] = $item ? $item->participations_validated_count : 0;
                $items[$year . ' - B - Others'][] = $item ? $item->participations_others_count : 0;
            }
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
}
