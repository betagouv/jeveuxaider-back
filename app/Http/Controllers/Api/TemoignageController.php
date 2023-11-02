<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTemoignageOrganisationSearch;
use App\Filters\FiltersTemoignageSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TemoignageCreateRequest;
use App\Http\Requests\Api\TemoignageUpdateRequest;
use App\Models\Reseau;
use App\Models\Structure;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TemoignageController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Temoignage::role($request->header('Context-Role')))
            ->with('participation.mission', 'participation.mission.structure', 'participation.profile', 'participation.profile.avatar')
            ->allowedFilters(
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('participation.mission.id'),
                AllowedFilter::exact('grade'),
                AllowedFilter::custom('search', new FiltersTemoignageSearch),
                AllowedFilter::custom('organisation', new FiltersTemoignageOrganisationSearch),
            )
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Temoignage $temoignage)
    {
        $temoignage->load(
            'participation.mission',
            'participation.mission.responsable',
            'participation.mission.responsable.user',
            'participation.mission.structure',
            'participation.profile'
        );

        return $temoignage;
    }

    public function update(TemoignageUpdateRequest $request, Temoignage $temoignage)
    {
        $temoignage->update($request->validated());

        return $temoignage;
    }

    public function publish(TemoignageUpdateRequest $request, Temoignage $temoignage)
    {
        $temoignage->update(['is_published' => true]);

        return $temoignage;
    }

    public function unpublish(TemoignageUpdateRequest $request, Temoignage $temoignage)
    {
        $temoignage->update(['is_published' => false]);

        return $temoignage;
    }

    public function store(TemoignageCreateRequest $request)
    {
        // Seulement si temoignage non existant.
        $temoignagesCount = Temoignage::where('participation_id', request('participation_id'))->count();
        if ($temoignagesCount > 0) {
            abort(422, 'Un témoignage existe déjà pour cette participation !');
        }

        return Temoignage::create($request->validated());
    }

    public function forOrganisation(Request $request, Structure $structure)
    {
        return Temoignage::with([
            'participation.mission',
            'participation.mission.structure',
            'participation.profile',
            'participation.profile.avatar',
        ])->where('grade', '>=', 4)->where('is_published', true)->ofStructure($structure->id)->inRandomOrder()->take(10)->get();
    }

    public function forReseau(Request $request, Reseau $reseau)
    {
        return Temoignage::with([
            'participation.mission',
            'participation.mission.structure.logo',
            'participation.profile',
        ])->where('grade', '>=', 4)->where('is_published', true)->ofReseau($reseau->id)->inRandomOrder()->take(10)->get();
    }
}
