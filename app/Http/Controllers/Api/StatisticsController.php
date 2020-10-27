<?php

namespace App\Http\Controllers\Api;

use App\Exports\CollectivitiesExport;
use App\Exports\DepartmentsExport;
use App\Exports\DomainesExport;
use App\Filters\FiltersCollectivitySearch;
use App\Filters\FiltersTagName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Collectivity;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Tag;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

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

    public function skills(Request $request)
    {
        return Tag::where('type', 'competence')->withCount('profiles')->orderBy('profiles_count', 'desc')->limit(10)->get();
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
                    'responsable_collectivity' => Profile::role($request->header('Context-Role'))->whereHas('collectivity')->count(),
                    'admin' => Profile::role($request->header('Context-Role'))
                        ->whereHas('user', function (Builder $query) {
                            $query->where('is_admin', true);
                        })->count(),
                    'invited' => Profile::role($request->header('Context-Role'))->doesntHave('user')->count(),
                ];

                break;
            case 'referent':
            case 'referent_regional':
            case 'responsable_collectivity':
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

    public function domaines(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'export') {
            return Excel::download(new DomainesExport($request->header('Context-Role')), 'domaines.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        $domaines = QueryBuilder::for(Tag::where('type', 'domaine'))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersTagName),
            ])
            ->defaultSort('name->fr')
            ->paginate(config('query-builder.results_per_page'));

        $stats = collect();

        foreach ($domaines as $domaine) {
            $missionsAvailableCollection = Mission::role($request->header('Context-Role'))->available()->domaine($domaine->id)->get();
            $places_left = $missionsAvailableCollection->sum('places_left');
            $participations_max = $missionsAvailableCollection->sum('participations_max');
            $stats->push([
                'key' => $domaine->id,
                'name' => $domaine->name,
                'image' => $domaine->image,
                'missions_count' => Mission::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
                'participations_count' => Participation::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
                'volontaires_count' => Profile::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
                'missions_available' => $missionsAvailableCollection->count(),
                'places_available' => $places_left,
                'places' => $participations_max,
                'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0
            ]);
        }

        return [
            'data' => $stats,
            'from' => $domaines->firstItem(),
            'to' => $domaines->lastItem(),
            'total' => $domaines->total(),
        ];
    }

    public function collectivities(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'export') {
            return Excel::download(new CollectivitiesExport(), 'collectivities.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        $datas = QueryBuilder::for(Collectivity::where('type', 'commune')->where('state', 'validated'))
            ->allowedFilters([
                'state',
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
            ])
            ->defaultSort('name')
            ->paginate(config('query-builder.results_per_page'));

        $stats = collect();

        foreach ($datas as $collectivity) {
            $missions = Mission::whereIn('zip', $collectivity->zips)->available()->get();
            $places_left = $missions->sum('places_left');
            $participations_max = $missions->sum('participations_max');
            $stats->push([
                'id' => $collectivity->id,
                'name' => $collectivity->name,
                'published' => $collectivity->published,
                'missions_count' => Mission::whereIn('zip', $collectivity->zips)->count(),
                'structures_count' => Structure::whereIn('zip', $collectivity->zips)->count(),
                'participations_count' => Participation::whereHas('mission', function (Builder $query) use ($collectivity) {
                    $query->whereIn('zip', $collectivity->zips);
                })->count(),
                'volontaires_count' => Profile::whereIn('zip', $collectivity->zips)->count(),
                'service_civique_count' => Profile::whereIn('zip', $collectivity->zips)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
                'missions_available' => $missions->count(),
                'places_available' => $places_left,
                'places' => $participations_max,
                'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0
            ]);
        }

        return [
            'data' => $stats,
            'from' => $datas->firstItem(),
            'to' => $datas->lastItem(),
            'total' => $datas->total(),
        ];
    }

    public function places(Request $request)
    {
        $missionsCollection = Mission::role($request->header('Context-Role'))
            ->available()
            ->get();

        $places_left = $missionsCollection->sum('places_left');
        $participations_max = $missionsCollection->sum('participations_max');

        return [
            'total_places_available' => $missionsCollection->sum('places_left'),
            'total_places' => $missionsCollection->sum('participations_max'),
            'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0,
            'total_missions_available' => $missionsCollection->count(),
        ];
    }

    public function departments(Request $request)
    {
        if ($request->has('type') && $request->input('type') == 'export') {
            return Excel::download(new DepartmentsExport($request->header('Context-Role')), 'departments.csv', \Maatwebsite\Excel\Excel::CSV);
        }

        $query = Collectivity::where('type', 'department')->where('state', 'validated');

        if ($request->header('Context-Role') == 'referent') {
            $query->where('department', Auth::guard('api')->user()->profile->referent_department);
        }

        if ($request->header('Context-Role') == 'referent_regional') {
            $query->whereIn('department', config('taxonomies.regions.departments')[Auth::guard('api')->user()->profile->referent_region]);
        }

        $collectivities = QueryBuilder::for($query)
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersCollectivitySearch),
            ])
            ->defaultSort('department')
            ->paginate(config('query-builder.results_per_page'));

        $stats = collect();

        foreach ($collectivities as $collectivity) {
            $missionsCollection = Mission::department($collectivity->department)
                ->hasPlacesLeft()
                ->available()
                ->get();

            $places_left = $missionsCollection->sum('places_left');
            $participations_max = $missionsCollection->sum('participations_max');

            $stats->push([
                'key' => $collectivity->department,
                'name' => $collectivity->name,
                'missions_count' => Mission::role($request->header('Context-Role'))->department($collectivity->department)->count(),
                'structures_count' => Structure::role($request->header('Context-Role'))->department($collectivity->department)->count(),
                'participations_count' => Participation::role($request->header('Context-Role'))->department($collectivity->department)->count(),
                'volontaires_count' => Profile::role($request->header('Context-Role'))
                    ->department($collectivity->department)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('context_role', 'volontaire');
                    })
                    ->count(),
                'service_civique_count' => Profile::role($request->header('Context-Role'))
                    ->department($collectivity->department)
                    ->whereHas('user', function (Builder $query) {
                        $query->where('service_civique', true);
                    })->count(),
                'missions_available' => $missionsCollection->count(),
                'places_available' => $places_left,
                'places' => $participations_max,
                'taux_occupation' => $participations_max ? round((($participations_max - $places_left) / $participations_max) * 100) : 0
            ]);
        }

        return [
            'data' => $stats,
            'from' => $collectivities->firstItem(),
            'to' => $collectivities->lastItem(),
            'total' => $collectivities->total(),
        ];
    }
}
