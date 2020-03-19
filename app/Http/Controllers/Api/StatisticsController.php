<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;

class StatisticsController extends Controller
{
    public function missions(Request $request)
    {
        $totalPlaces = Mission::role($request->header('Context-Role'))->sum('participations_max');
        $participations = Mission::role($request->header('Context-Role'))->with('participations')->pluck('participations_count')->sum();
        $places_left = $totalPlaces - $participations;

        return [
            'total' => Mission::role($request->header('Context-Role'))->count(),
            'places_left' => $places_left,
            'participations' => $participations
        ];
    }

    public function structures(Request $request)
    {
        return [
            'total' => Structure::role($request->header('Context-Role'))->count(),
        ];
    }

    public function profiles(Request $request)
    {
        return [
            'total' => Profile::role($request->header('Context-Role'))->count(),
        ];
    }

    public function participations(Request $request)
    {
        return [
            'total' => Participation::role($request->header('Context-Role'))->count(),
            'waiting' => Participation::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
            'current' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission en cours', 'Mission validée'])->count(),
            'done' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission effectuée'])->count(),
            'other' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission refusée','Mission abandonnée', 'Mission annulée', 'Archivée'])->count(),
        ];
    }
}
