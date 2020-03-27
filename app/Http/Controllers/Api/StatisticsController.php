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
        
        // $totalPlaces = Mission::role($request->header('Context-Role'))->sum('participations_max');
        // $participations = Mission::role($request->header('Context-Role'))->with('participations')->pluck('participations_count')->sum();
        // $places_left = $totalPlaces - $participations;

        // return [
        //     'total' => Mission::role($request->header('Context-Role'))->count(),
        //     'places_left' => $places_left,
        //     'participations' => $participations
        // ];

        // $missionsCollection = Mission::role($request->header('Context-Role'))->without(['structure','tuteur'])->available()->hasPlacesLeft()->get();

        // return [
        //     'total' => Mission::role($request->header('Context-Role'))->count(),
        //     'places_available' => $missionsCollection->mapWithKeys(function ($item) {
        //         return ['places_left_'.$item->id=> $item->participations_max - $item->participations_count];
        //     })->sum(),
        //     'missions_available' => $missionsCollection->count(),
        // ];

        return [
            'total' => Mission::role($request->header('Context-Role'))->count(),
            'validated' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'canceled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count(),
            'signaled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
            'other' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Brouillon'])->count(),
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
            'other' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission refusée','Mission abandonnée', 'Mission annulée', 'Mission signalée'])->count(),
        ];
    }

    public function analytics(Request $request)
    {
        $departements = config('taxonomies.departments.terms');
        $datas = collect();

        $missionsCollection = Mission::role($request->header('Context-Role'))
                ->without(['structure','tuteur'])
                ->available()
                ->hasPlacesLeft()
                ->get();

        foreach ($departements as $key => $value) {
            $departmentCollection = $missionsCollection->filter(function ($item) use ($key) {
                return $item->department == $key;
            });

            $datas->push([
                'key' => $key,
                'name' => $value,
                'missions_count' => Mission::role($request->header('Context-Role'))->department($key)->count(),
                'structures_count' => Structure::role($request->header('Context-Role'))->department($key)->count(),
                'participations_count' => Participation::role($request->header('Context-Role'))->department($key)->count(),
                'missions_available' => $departmentCollection->count(),
                'places_available' => $departmentCollection->mapWithKeys(function ($item) {
                    return ['places_left_'.$item->id=> $item->participations_max - $item->participations_count];
                })->sum(),
            ]);
        }

        return [
            'total_places_available' => $missionsCollection->mapWithKeys(function ($item) {
                return ['places_left_'.$item->id=> $item->participations_max - $item->participations_count];
            })->sum(),
            'total_missions_available' => $missionsCollection->count(),
            'departments' => $datas
        ];
    }
}
