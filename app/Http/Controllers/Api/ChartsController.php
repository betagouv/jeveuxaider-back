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

class ChartsController extends Controller
{

    public $startDate;
    public $endDate;

    public function __construct(Request $request)
    {
        $this->startYear = 2020;
        $this->endYear = date('Y');
    }

    public function organisationsByDate(Request $request)
    {
        $items = [];

        // SELECT date_trunc('month', "public"."profiles"."created_at") AS "created_at", count(*) AS "count"
        // FROM "public"."profiles"
        // GROUP BY date_trunc('month', "public"."profiles"."created_at")
        // ORDER BY date_trunc('month', "public"."profiles"."created_at") ASC

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $items[$year][]  = Structure::role($request->header('Context-Role'))
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->count();
            }
        }

        return $items;
    }

    public function missionsByDate(Request $request)
    {
        $items = [];

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $items[$year][]  = Mission::role($request->header('Context-Role'))
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->count();
            }
        }

        return $items;
    }

    public function participationsByDate(Request $request)
    {
        $items = [];

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $items[$year][]  = Participation::role($request->header('Context-Role'))
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->count();
            }
        }

        return $items;
    }

    public function utilisateursByDate(Request $request)
    {
        $items = [];

        for ($year = $this->startYear; $year <= $this->endYear; $year++) {
            for ($month = 1; $month < 13; $month++) {
                $items[$year][]  = Profile::role($request->header('Context-Role'))
                    ->whereYear('created_at', '=', $year)
                    ->whereMonth('created_at', '=', $month)
                    ->count();
            }
        }

        return $items;
    }

}
