<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Young;

class StatisticsController extends Controller
{
    public function missions(Request $request)
    {
        $totalPlaces = Mission::role($request->header('Context-Role'))->sum('participations_max');
        $youngs = Mission::role($request->header('Context-Role'))->with('youngs')->pluck('youngs_count')->sum();
        $places_left = $totalPlaces - $youngs;

        return [
            'total' => Mission::role($request->header('Context-Role'))->count(),
            'places_left' => $places_left,
            'youngs' => $youngs
        ];
    }

    public function structures(Request $request)
    {
        return [
            'total' => Structure::role($request->header('Context-Role'))->count(),
        ];
    }

    public function youngs(Request $request)
    {
        return [
            'total' => Young::role($request->header('Context-Role'))->count(),
            'waiting' => Young::role($request->header('Context-Role'))->whereIn('state', ['En attente de mission'])->count(),
            'current' => Young::role($request->header('Context-Role'))->whereIn('state', ['Mission en cours', 'Mission proposée'])->count(),
            'done' => Young::role($request->header('Context-Role'))->whereIn('state', ['Mission effectuée'])->count(),
            'other' => Young::role($request->header('Context-Role'))->whereIn('state', ['Mission refusée','Abandon de mission', 'Exclusion de la mission', 'Arrêt de la mission'])->count(),
        ];
    }

    public function profiles(Request $request)
    {
        return [
            'total' => Profile::role($request->header('Context-Role'))->count(),
        ];
    }
}
