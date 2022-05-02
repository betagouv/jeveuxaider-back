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
    public $year;
    public $month;
    public $date;
    public $startDate;
    public $endDate;

    public function __construct(Request $request)
    {
        $this->year = date('Y');

        if ($request->input('period') == 'all') {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
        elseif ($request->input('period') == 'year') {
            $this->year = $request->input('year');
            $this->date = Carbon::parse($request->input('year')."-01-01");
            $this->startDate = $this->date->startOfYear()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfYear()->format('Y-m-d H:i:s');
        }
        elseif ($request->input('period') == 'month') {
            $this->year = $request->input('year');
            $this->date = Carbon::parse($request->input('year')."-".$request->input('month')."-01");
            $this->startDate = $this->date->startOfMonth()->format('Y-m-d H:i:s');
            $this->endDate = $this->date->endOfMonth()->format('Y-m-d H:i:s');
        }
        else {
            $this->startDate = Carbon::create(2000, 01, 01, 0, 0, 0)->format('Y-m-d H:i:s');
            $this->endDate = Carbon::now()->format('Y-m-d H:i:s');
        }
    }

    public function organisationsByDate(Request $request)
    {
        $items = [];

        for ($i = 1; $i < 13; $i++) {
            $items[] = Structure::role($request->header('Context-Role'))
                ->whereYear('created_at', '=', $this->year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return $items;
    }

    public function missionsByDate(Request $request)
    {
        $items = [];

        for ($i = 1; $i < 13; $i++) {
            $items[] = Mission::role($request->header('Context-Role'))
                ->whereYear('created_at', '=', $this->year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return $items;
    }

    public function participationsByDate(Request $request)
    {
        $items = [];

        for ($i = 1; $i < 13; $i++) {
            $items[] = Participation::role($request->header('Context-Role'))
                ->whereYear('created_at', '=', $this->year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return $items;
    }

    public function utilisateursByDate(Request $request)
    {
        $items = [];

        for ($i = 1; $i < 13; $i++) {
            $items[] = Profile::role($request->header('Context-Role'))
                ->whereYear('created_at', '=', $this->year)
                ->whereMonth('created_at', '=', $i)
                ->count();
        }

        return $items;
    }

   

}
