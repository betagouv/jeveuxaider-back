<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class StatisticsController extends Controller
{
    public function global(Request $request)
    {
        return [
            'missions' => Mission::count(),
            'structures' => Structure::count(),
            'participations' => Participation::count(),
            'profiles' => Profile::count(),
        ];
    }

    public function missions(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Mission::role($request->header('Context-Role'))->count(),
                'month' => Mission::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Mission::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        return [
            'total' => Mission::role($request->header('Context-Role'))->count(),
            'waiting' => Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'finished' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Terminée'])->count(),
            'canceled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count(),
            'signaled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
            'draft' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Brouillon'])->count(),
        ];
    }

    public function structures(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Structure::role($request->header('Context-Role'))->count(),
                'month' => Structure::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Structure::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        return [
            'total' => Structure::role($request->header('Context-Role'))->count(),
            'validated' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'signaled' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
            'waiting' => Structure::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
        ];
    }

    public function profiles(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Profile::role($request->header('Context-Role'))->count(),
                'month' => Profile::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Profile::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        switch ($request->header('Context-Role')) {
            case 'admin':
            case 'analyste':
                return [
                    'total' => Profile::role($request->header('Context-Role'))->count(),
                    'volontaire' => Profile::role($request->header('Context-Role'))
                        ->whereHas('user', function (Builder $query) {
                            $query->where('context_role', 'volontaire');
                        })->count(),
                    'service_civique' => Profile::role($request->header('Context-Role'))
                        ->whereHas('user', function (Builder $query) {
                            $query->where('service_civique', true);
                        })->count(),
                    'responsable' => Profile::role($request->header('Context-Role'))->whereHas('missions')->orWhereHas('structures')->count(),
                    'referent' => Profile::role($request->header('Context-Role'))->whereNotNull('referent_department')->count(),
                    'referent_regional' => Profile::role($request->header('Context-Role'))->whereNotNull('referent_region')->count(),
                    'superviseur' => Profile::role($request->header('Context-Role'))->whereHas('reseau')->count(),
                    'admin' => Profile::role($request->header('Context-Role'))
                        ->whereHas('user', function (Builder $query) {
                            $query->where('is_admin', true);
                        })->count(),
                    'invited' => Profile::role($request->header('Context-Role'))->doesntHave('user')->count(),
                ];

                break;
            case 'referent':
            case 'referent_regional':
            case 'superviseur':
                $total = Profile::role($request->header('Context-Role'))->count();
                $volontaire = Profile::role($request->header('Context-Role'))
                    ->whereHas('user', function (Builder $query) {
                        $query->where('context_role', 'volontaire');
                    })
                    ->count();
                return [
                    'total' => $total,
                    'volontaire' => $volontaire,
                    'responsable' => $total - $volontaire,
                    'service_civique' => Profile::role($request->header('Context-Role'))
                        ->whereHas('user', function (Builder $query) {
                            $query->where('service_civique', true);
                        })->count(),
                ];
                break;
        }
    }

    public function participations(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'light') {
            return [
                'total' => Participation::role($request->header('Context-Role'))->count(),
                'month' => Participation::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
                'week' => Participation::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
            ];
        }

        return [
            'total' => Participation::role($request->header('Context-Role'))->count(),
            'waiting' => Participation::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
            'validated' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
            'refused' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Refusée'])->count(),
            'done' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Effectuée'])->count(),
            'canceled' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count()
        ];
    }

    public function departments(Request $request)
    {
        $departements = config('taxonomies.departments.terms');
        $datas = collect();

        $missionsCollection = Mission::role($request->header('Context-Role'))
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
                'volontaires_count' => Profile::role($request->header('Context-Role'))
                    ->department($key)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('context_role', 'volontaire');
                    })
                    ->count(),
                'service_civique_count' => Profile::role($request->header('Context-Role'))
                    ->department($key)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
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
