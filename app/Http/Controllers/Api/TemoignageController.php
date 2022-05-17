<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTemoignageSearch;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TemoignageCreateRequest;
use App\Http\Requests\Api\TemoignageUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TemoignageController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Temoignage::role($request->header('Context-Role')))
            ->with('participation.mission', 'participation.mission.structure', 'participation.profile')
            ->allowedFilters(
                AllowedFilter::exact('is_published'),
                AllowedFilter::exact('participation.mission.id'),
                AllowedFilter::exact('grade'),
                AllowedFilter::custom('search', new FiltersTemoignageSearch),
            )
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Temoignage $temoignage)
    {
        $temoignage->load(
            'participation.mission',
            'participation.mission.responsable',
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

    public function store(TemoignageCreateRequest $request)
    {
        // Seulement si temoignage non existant.
        $temoignagesCount = Temoignage::where('participation_id', request("participation_id"))->count();
        if ($temoignagesCount > 0) {
            abort(422, "Un témoignage existe déjà pour cette participation !");
        }

        return Temoignage::create($request->validated());
    }
}
