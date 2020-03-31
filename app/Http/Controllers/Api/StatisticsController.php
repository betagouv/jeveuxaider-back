<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StatisticsController extends Controller
{
    public function missions(Request $request)
    {
        return [
            'total' => Mission::role($request->header('Context-Role'))->count(),
            'waiting' => Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'canceled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count(),
            'signaled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
            'draft' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Brouillon'])->count(),
        ];
    }

    public function structures(Request $request)
    {
        return [
            'total' => Structure::role($request->header('Context-Role'))->count(),
            'validated' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'signaled' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
            'waiting' => Structure::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
        ];
    }

    public function profiles(Request $request)
    {
        return [
            'total' => Profile::role($request->header('Context-Role'))->count(),
            'volontaire' => Profile::role($request->header('Context-Role'))
                ->whereHas('user', function (Builder $query) {
                    $query->where('context_role', 'volontaire');
                })->count(),
            'responsable' => Profile::role($request->header('Context-Role'))->whereHas('missionsAsTuteur')->orWhereHas('structures')->count(),
            'referent' => Profile::role($request->header('Context-Role'))->whereNotNull('referent_department')->count(),
            'superviseur' => Profile::role($request->header('Context-Role'))->whereHas('reseau')->count(),
            'admin' => Profile::role($request->header('Context-Role'))
                ->whereHas('user', function (Builder $query) {
                    $query->where('is_admin', true);
                })->count(),
            'invited' => Profile::role($request->header('Context-Role'))->doesntHave('user')->count(),
        ];
    }

    public function participations(Request $request)
    {
        return [
            'total' => Participation::role($request->header('Context-Role'))->count(),
            'waiting' => Participation::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission validée'])->count(),
            'current' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission en cours'])->count(),
            'done' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission effectuée'])->count(),
            'canceled' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission annulée'])->count(),
            'signaled' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission signalée'])->count(),
            'abandoned' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Mission abandonnée'])->count(),
        ];
    }

    public function analytics(Request $request)
    {
        $departements = config('taxonomies.departments.terms');
        $datas = collect();

        $missionsCollection = Mission::role($request->header('Context-Role'))
            ->without(['structure', 'tuteur'])
            ->available()
            ->hasPlacesLeft()
            ->get();

        // Filter department based on user
        if ($request->header('Context-Role') == 'referent') {
            $referentDepartement = Auth::guard('api')->user()->profile->referent_department;
            foreach ($departements as $key => $departement) {
                if ($key != $referentDepartement) {
                    unset($departements[$key]);
                }
            }
        }
        if ($request->header('Context-Role') == 'referent_regional') {
            $referentRegionalDepartements = config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region];
            foreach ($departements as $key => $departement) {
                if (!in_array($key, $referentRegionalDepartements)) {
                    unset($departements[$key]);
                }
            }
        }

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
                    return ['places_left_' . $item->id => $item->participations_max - $item->participations_count];
                })->sum(),
            ]);
        }

        return [
            'total_places_available' => $missionsCollection->mapWithKeys(function ($item) {
                return ['places_left_' . $item->id => $item->participations_max - $item->participations_count];
            })->sum(),
            'total_missions_available' => $missionsCollection->count(),
            'departments' => $datas
        ];
    }
}
