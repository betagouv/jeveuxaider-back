<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reseau;
use App\Models\Structure;
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
    public $structureId;
    public $reseauId;

    public function __construct(Request $request)
    {
        $this->startYear = 2020;
        $this->endYear = date('Y');

        if($request->header('Context-Role') === 'responsable') {
            $structure = Structure::find(Auth::guard('api')->user()->contextable_id);
            $this->structureId = $structure->id;
        } else {
            $this->structureId = $request->input('structure');
        }

        if($request->header('Context-Role') === 'tete_de_reseau') {
            $reseau = Reseau::find(Auth::guard('api')->user()->contextable_id);
            $this->reseauId = $reseau->id;
        } else {
            $this->reseauId = $request->input('reseau');
        }

        if($request->input('start_date')) {
            $this->startDate = Carbon::createFromFormat('Y-m-d', $request->input('start_date'))->hour(0)->minute(0)->second(0);
        } else {
            if($request->header('Context-Role') === 'responsable') {
                $this->startDate = $structure->created_at->format('Y-m-d');
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

        $results = DB::table('participations')
        ->select(
            DB::raw("date_part('year', participations.created_at) as year"),
            DB::raw("date_part('month', participations.created_at) as month"),
            DB::raw("SUM(CASE WHEN participations.state = 'Validée' THEN 1 ELSE 0 END) as participations_validated_count"),
            DB::raw("SUM(CASE WHEN participations.state != 'Validée' THEN 1 ELSE 0 END) as participations_others_count"),
            DB::raw("COUNT(*) AS count")
        )
        ->leftJoin('missions', 'missions.id', '=', 'participations.mission_id')
        ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
        ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
        ->when($this->department, function ($query) {
            $query->where('structures.department', $this->department);
        })
        ->when($this->reseauId, function ($query) {
            $query->where('reseau_structure.reseau_id', $this->reseauId);
        })
        ->groupBy(DB::raw("date_trunc('month', participations.created_at)"), 'year', 'month')
        ->orderBy(DB::raw("date_trunc('month', participations.created_at)"), 'ASC')
        ->get();

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

    public function participationsByPeriod(Request $request)
    {
        // Subquery to get counts of participations per month
        $participationsSubquery = DB::table('participations')
            ->select(
                DB::raw("date_trunc('month', participations.created_at) AS created_at"),
                DB::raw("count(*) AS count")
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
                DB::raw("COALESCE(p.count, 0) AS count")
            )
            ->leftJoinSub($participationsSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'ASC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->date = Carbon::parse($item->month_start)->format('Y-m-d');
            return $item;
        });
    }

    public function utilisateursByPeriod(Request $request)
    {
        // Subquery to get counts of profiles per month
        $profilesSubquery = DB::table('profiles')
            ->select(
                DB::raw("date_trunc('month', profiles.created_at) AS created_at"),
                DB::raw("count(*) AS count")
            )
            ->whereBetween('profiles.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('profiles.department', $this->department);
            })
            ->groupBy(DB::raw("date_trunc('month', profiles.created_at)"));

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
                DB::raw("COALESCE(p.count, 0) AS count")
            )
            ->leftJoinSub($profilesSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'ASC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->date = Carbon::parse($item->month_start)->format('Y-m-d');
            return $item;
        });
    }

    public function organisationsByPeriod(Request $request)
    {
        // Subquery to get counts of structures per month
        $structuresSubquery = DB::table('structures')
            ->select(
                DB::raw("date_trunc('month', structures.created_at) AS created_at"),
                DB::raw("count(*) AS count")
            )
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'structures.id')
            ->whereBetween('structures.created_at', [$this->startDate, $this->endDate])
            ->when($this->department, function ($query) {
                $query->where('structures.department', $this->department);
            })
            ->when($this->reseauId, function ($query) {
                $query->where('reseau_structure.reseau_id', $this->reseauId);
            })
            ->groupBy(DB::raw("date_trunc('month', structures.created_at)"));

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
                DB::raw("COALESCE(p.count, 0) AS count")
            )
            ->leftJoinSub($structuresSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'ASC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->date = Carbon::parse($item->month_start)->format('Y-m-d');
            return $item;
        });
    }

    public function missionsByPeriod(Request $request)
    {
        // Subquery to get counts of missions per month
        $missionsSubquery = DB::table('missions')
            ->select(
                DB::raw("date_trunc('month', missions.created_at) AS created_at"),
                DB::raw("count(*) AS count")
            )
            ->leftJoin('structures', 'structures.id', '=', 'missions.structure_id')
            ->leftJoin('reseau_structure', 'reseau_structure.structure_id', '=', 'missions.structure_id')
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
                DB::raw("COALESCE(p.count, 0) AS count")
            )
            ->leftJoinSub($missionsSubquery, 'p', function ($join) {
                $join->on('date_series.month_start', '=', 'p.created_at');
            })
            ->orderBy('date_series.month_start', 'ASC')
            ->get();

        $collection = collect($results);

        return $collection->map(function ($item) {
            $item->date = Carbon::parse($item->month_start)->format('Y-m-d');
            return $item;
        });
    }
}
