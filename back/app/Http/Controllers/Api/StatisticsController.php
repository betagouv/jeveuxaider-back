<?php

namespace App\Http\Controllers\Api;

use App\Exports\DomainesExport;
use App\Filters\FiltersTagName;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\Tag;
use App\Models\Territoire;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class StatisticsController extends Controller
{
    public function dashboard(Request $request)
    {
        $missionsAvailable = Mission::role($request->header('Context-Role'))
            ->available()
            ->get();

        $placesLeft = $missionsAvailable->sum('places_left');
        $placesOffered = $missionsAvailable->sum('participations_max');

        switch($request->header('Context-Role')){
            case 'admin':
                return [
                    'organisations' => Structure::count(),
                    'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
                    'missions' => Mission::count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::count(),
                    'participations_validated' => Participation::where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_occupation_rate' => $placesOffered ? round(($placesLeft / $placesOffered) * 100) : 0,
                    'users' => Profile::count(),
                    'users_benevoles' => Profile::benevole()->count(),
                ];
                break;
            case 'referent':
            case 'referent_regional':
                return [
                    'organisations' => Structure::role($request->header('Context-Role'))->count(),
                    'organisations_actives' => $missionsAvailable->pluck('structure_id')->unique()->count(),
                    'missions' => Mission::role($request->header('Context-Role'))->count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::role($request->header('Context-Role'))->count(),
                    'participations_validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_occupation_rate' => $placesOffered ? round(($placesLeft / $placesOffered) * 100) : 0,
                ];
                break;
            case 'responsable':
                return [
                    'missions' => Mission::role($request->header('Context-Role'))->count(),
                    'missions_actives' => $missionsAvailable->count(),
                    'participations' => Participation::role($request->header('Context-Role'))->count(),
                    'participations_validated' => Participation::role($request->header('Context-Role'))->where('state', 'Validée')->count(),
                    'places_left' => $placesLeft,
                    'places_occupation_rate' => $placesOffered ? round(($placesLeft / $placesOffered) * 100) : 0,
                ];
                break;
        }

    }

    public function organisations(Request $request, Structure $structure) 
    {
        $missionsStateCount = array_map(function($state) use ($structure) {
            return $structure->missions()->where('state', $state)->count();
        }, config('taxonomies.mission_workflow_states.terms'));

        $participationsStateCount = array_map(function($state) use ($structure) {
            return $structure->participations()->where('participations.state', $state)->count();
        }, config('taxonomies.participation_workflow_states.terms'));

        return [
            'missions_total' => $structure->missions()->count(),
            'missions_available' => $structure->missions()->available()->count(),
            'missions_state' =>  $missionsStateCount,
            'participations_total' => $structure->participations()->count(),
            'participations_state' =>  $participationsStateCount,
        ];
    }


    // public function global(Request $request)
    // {
    //     return [
    //         'missions' => Mission::count(),
    //         'structures' => Structure::count(),
    //         'participations' => Participation::count(),
    //         'profiles' => Profile::count(),
    //     ];
    // }

    // public function missions(Request $request)
    // {
    //     if ($request->has('type') && $request->input('type') == 'light') {
    //         return [
    //             'total' => Mission::role($request->header('Context-Role'))->count(),
    //             'month' => Mission::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //             'week' => Mission::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //         ];
    //     }

    //     return [
    //         'total' => Mission::role($request->header('Context-Role'))->count(),
    //         'waiting' => Mission::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
    //         'validated' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
    //         'finished' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Terminée'])->count(),
    //         'canceled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count(),
    //         'signaled' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
    //         'draft' => Mission::role($request->header('Context-Role'))->whereIn('state', ['Brouillon'])->count(),
    //     ];
    // }

    // public function structures(Request $request)
    // {
    //     if ($request->has('type') && $request->input('type') == 'light') {
    //         return [
    //             'total' => Structure::role($request->header('Context-Role'))->count(),
    //             'month' => Structure::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //             'week' => Structure::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //         ];
    //     }

    //     return [
    //         'total' => Structure::role($request->header('Context-Role'))->count(),
    //         'validated' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
    //         'signaled' => Structure::role($request->header('Context-Role'))->whereIn('state', ['Signalée'])->count(),
    //         'waiting' => Structure::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
    //     ];
    // }

    // public function skills(Request $request)
    // {
    //     return Tag::where('type', 'competence')->withCount('profiles')->orderBy('profiles_count', 'desc')->limit(10)->get();
    // }

    // public function profiles(Request $request)
    // {
    //     if ($request->has('type') && $request->input('type') == 'light') {
    //         return [
    //             'total' => Profile::role($request->header('Context-Role'))->count(),
    //             'month' => Profile::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //             'week' => Profile::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //         ];
    //     }

    //     switch ($request->header('Context-Role')) {
    //         case 'admin':
    //         case 'analyste':
    //             return [
    //                 'total' => Profile::role($request->header('Context-Role'))->count(),
    //                 'volontaire' => Profile::role($request->header('Context-Role'))
    //                     ->whereHas('user', function (Builder $query) {
    //                         $query->where('context_role', 'volontaire');
    //                     })->count(),
    //                 'service_civique' => Profile::role($request->header('Context-Role'))
    //                     ->whereHas('user', function (Builder $query) {
    //                         $query->where('service_civique', true);
    //                     })->count(),
    //                 'responsable' => Profile::role($request->header('Context-Role'))->whereHas('missions')->orWhereHas('structures')->count(),
    //                 'referent' => Profile::role($request->header('Context-Role'))->whereNotNull('referent_department')->count(),
    //                 'referent_regional' => Profile::role($request->header('Context-Role'))->whereNotNull('referent_region')->count(),
    //                 'tete_de_reseau' => Profile::role($request->header('Context-Role'))->whereNotNull('tete_de_reseau_id')->count(),
    //                 'admin' => Profile::role($request->header('Context-Role'))
    //                     ->whereHas('user', function (Builder $query) {
    //                         $query->where('is_admin', true);
    //                     })->count(),
    //             ];

    //             break;
    //         case 'referent':
    //         case 'referent_regional':
    //         case 'tete_de_reseau':
    //             $total = Profile::role($request->header('Context-Role'))->count();
    //             $volontaire = Profile::role($request->header('Context-Role'))
    //                 ->whereHas('user', function (Builder $query) {
    //                     $query->where('context_role', 'volontaire');
    //                 })
    //                 ->count();
    //             return [
    //                 'total' => $total,
    //                 'volontaire' => $volontaire,
    //                 'responsable' => $total - $volontaire,
    //                 'service_civique' => Profile::role($request->header('Context-Role'))
    //                     ->whereHas('user', function (Builder $query) {
    //                         $query->where('service_civique', true);
    //                     })->count(),
    //             ];
    //             break;
    //     }
    // }

    // public function participations(Request $request)
    // {
    //     if ($request->has('type') && $request->input('type') == 'light') {
    //         return [
    //             'total' => Participation::role($request->header('Context-Role'))->count(),
    //             'month' => Participation::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //             'week' => Participation::role($request->header('Context-Role'))->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //         ];
    //     }

    //     return [
    //         'total' => Participation::role($request->header('Context-Role'))->count(),
    //         'waiting' => Participation::role($request->header('Context-Role'))->whereIn('state', ['En attente de validation'])->count(),
    //         'validated' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Validée'])->count(),
    //         'refused' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Refusée'])->count(),
    //         'canceled' => Participation::role($request->header('Context-Role'))->whereIn('state', ['Annulée'])->count()
    //     ];
    // }

    // public function domaines(Request $request)
    // {
    //     if ($request->has('type') && $request->input('type') == 'export') {
    //         return Excel::download(new DomainesExport($request->header('Context-Role')), 'domaines.csv', \Maatwebsite\Excel\Excel::CSV);
    //     }

    //     $domaines = QueryBuilder::for(Tag::where('type', 'domaine'))
    //         ->allowedFilters([
    //             AllowedFilter::custom('search', new FiltersTagName),
    //         ])
    //         ->defaultSort('name->fr')
    //         ->paginate(config('query-builder.results_per_page'));

    //     $stats = collect();

    //     foreach ($domaines as $domaine) {
    //         $missionsAvailableCollection = Mission::role($request->header('Context-Role'))
    //             ->domaine($domaine->id)
    //             ->hasPlacesLeft()
    //             ->available()
    //             ->get();

    //         $missionsCollection = Mission::role($request->header('Context-Role'))
    //             ->domaine($domaine->id)
    //             ->whereIn('state', ['Validée', 'Terminée'])
    //             ->get();

    //         $places_available_left = $missionsCollection->where('state', 'Validée')->sum('places_left');
    //         $places_offered = $missionsCollection->where('state', 'Validée')->sum('participations_max');
    //         $total_participations_max = $missionsCollection->sum('participations_max');

    //         $stats->push([
    //             'key' => $domaine->id,
    //             'name' => $domaine->name,
    //             'image' => $domaine->image,
    //             'missions_count' => Mission::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
    //             'participations_count' => Participation::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
    //             'volontaires_count' => Profile::role($request->header('Context-Role'))->domaine($domaine->id)->count(),
    //             'service_civique_count' => Profile::role($request->header('Context-Role'))->domaine($domaine->id)
    //                 ->whereHas('user', function (Builder $query) {
    //                     $query->where('service_civique', true);
    //                 })->count(),
    //             'missions_available' => $missionsAvailableCollection->count(),
    //             'organisations_active' => $missionsAvailableCollection->pluck('structure_id')->unique()->count(),
    //             'places_available' => $missionsAvailableCollection->sum('places_left'),
    //             'total_offered_places' => $total_participations_max,
    //             'occupation_rate' => $places_offered ? ($places_available_left / $places_offered) * 100 : 0,
    //         ]);
    //     }

    //     return [
    //         'data' => $stats,
    //         'from' => $domaines->firstItem(),
    //         'to' => $domaines->lastItem(),
    //         'total' => $domaines->total(),
    //     ];
    // }

    // public function places(Request $request)
    // {
    //     $missionsCollection = Mission::role($request->header('Context-Role'))
    //         ->available()
    //         ->hasPlacesLeft()
    //         ->get();

    //     return [
    //         'total_places_available' => $missionsCollection->sum('places_left'),
    //         'total_structures_active' => $missionsCollection->pluck('structure_id')->unique()->count(),
    //         'total_missions_available' => $missionsCollection->count(),
    //     ];
    // }

    // public function occupationRate(Request $request)
    // {
    //     $missionsCollection = Mission::role($request->header('Context-Role'))
    //         ->whereIn('state', ['Validée', 'Terminée'])
    //         ->get();

    //     $missionsAvailableCollection = Mission::role($request->header('Context-Role'))
    //         ->whereIn('state', ['Validée'])
    //         ->get();

    //     $places_available_left = $missionsAvailableCollection->sum('places_left');
    //     $places_offered = $missionsAvailableCollection->sum('participations_max');
    //     $total_participations_max = $missionsCollection->sum('participations_max');

    //     return [
    //         'total_offered_places' => $total_participations_max,
    //         'occupation_rate' => $places_offered ? ($places_available_left / $places_offered) * 100 : 0,
    //     ];
    // }

    // public function online(Request $request)
    // {
    //     return [
    //         'total' => Profile::role($request->header('Context-Role'))->whereHas('user', function (Builder $query) {
    //             $query->where('last_online_at', '>', Carbon::now()->subMinutes(5));
    //         })->count(),
    //         'month' => Profile::role($request->header('Context-Role'))->whereHas('user', function (Builder $query) {
    //             $query->where('last_online_at', '>', Carbon::now()->subDays(30));
    //         })->count(),
    //         'week' => Profile::role($request->header('Context-Role'))->whereHas('user', function (Builder $query) {
    //             $query->where('last_online_at', '>', Carbon::now()->subDays(7));
    //         })->count(),
    //     ];
    // }

    // public function fetch(Request $request, $type, $id)
    // {
    //     if ($type == 'territoires') {
    //         $this->authorize('viewStats', Territoire::find($id));
    //         $organisations = Structure::ofTerritoire($id);
    //         $missions = Mission::ofTerritoire($id);
    //         $participations = Participation::ofTerritoire($id);
    //         return [
    //             'organisations' => [
    //                 'total' => $organisations->count(),
    //                 'month' => $organisations->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $organisations->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ],
    //             'missions' => [
    //                 'total' => $missions->count(),
    //                 'month' => $missions->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $missions->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ],
    //             'participations' => [
    //                 'total' => $participations->count(),
    //                 'month' => $participations->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $participations->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ]
    //         ];
    //     }

    //     if ($type == 'organisations') {
    //         $missions = Mission::where('structure_id', $id);
    //         $participations = Participation::whereHas('mission', function (Builder $query) use ($id) {
    //             $query->where('structure_id', $id);
    //         });
    //         return [
    //             'missions' => [
    //                 'total' => $missions->count(),
    //                 'month' => $missions->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $missions->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ],
    //             'participations' => [
    //                 'total' => $participations->count(),
    //                 'month' => $participations->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $participations->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ]
    //         ];
    //     }

    //     if ($type == 'missions') {
    //         $participations = Participation::where('mission_id', $id);
    //         return [
    //             'participations' => [
    //                 'total' => $participations->count(),
    //                 'month' => $participations->where('created_at', '>=', Carbon::today()->subDays(30))->count(),
    //                 'week' => $participations->where('created_at', '>=', Carbon::today()->subDays(7))->count()
    //             ]
    //         ];
    //     }
    // }
}
