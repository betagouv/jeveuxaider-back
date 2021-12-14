<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Structure;
use Illuminate\Database\Eloquent\Builder;
use App\Services\ApiEngagement;

class EngagementController extends Controller
{
    public function feed()
    {
        $structuresNotInApi = [25, 7383, 5577]; // Bénénovat
        $missions = Mission::whereHas('structure', function (Builder $query) use ($structuresNotInApi) {
            $query->where('state', 'Validée')
                  ->whereNotIn('id', $structuresNotInApi);
        })->where('state', 'Validée')->where('places_left', '>', 0)->get();

        return response()->view('flux-api-engagement', compact('missions'))->header('Content-Type', 'text/xml');
    }

    public function organisations()
    {
        $organisations = Structure::with('reseau:id,name')->where('state', 'Validée')->get();

        return response()->view('flux-api-engagement-organisations', compact('organisations'))->header('Content-Type', 'text/xml');
    }

    public function import()
    {
        return (new ApiEngagement())->import();
    }

    public function delete()
    {
        return (new ApiEngagement())->delete();
    }
}
