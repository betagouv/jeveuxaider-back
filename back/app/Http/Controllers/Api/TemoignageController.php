<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersTemoignageSearch;
use App\Models\Temoignage;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\TemoignageCreateRequest;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class TemoignageController extends Controller
{
    public function index(Request $request)
    {
        // @todo: optimiser les relations,
        // ne sont nécessaires que pour le volet lorsqu'il est ouvert
        return QueryBuilder::for(Temoignage::role($request->header('Context-Role')))
            ->with('participation', 'participation.profile', 'participation.mission')
            ->allowedFilters(
                AllowedFilter::exact('participation.mission.id'),
                AllowedFilter::exact('grade'),
                AllowedFilter::custom('search', new FiltersTemoignageSearch),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function fromParticipation(Request $request, Participation $participation)
    {
        return Temoignage::where('participation_id', $participation->id)->first();
    }

    public function store(TemoignageCreateRequest $request)
    {
        // Seulement si temoignage non existant.
        $temoignagesCount = Temoignage::where('participation_id', request("participation_id"))->count();
        if ($temoignagesCount > 0) {
            abort(403, "Un témoignage existe déjà pour cette participation !");
        }

        return Temoignage::create($request->validated());
    }
}
