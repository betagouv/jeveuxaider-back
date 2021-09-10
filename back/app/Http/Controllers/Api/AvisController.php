<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersAvisSearch;
use App\Models\Avis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AvisCreateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class AvisController extends Controller
{
    public function index(Request $request)
    {
        // @todo: optimiser les relations,
        // ne sont nécessaires que pour le volet lorsqu'il est ouvert
        return QueryBuilder::for(Avis::role($request->header('Context-Role')))
            ->with('participation', 'participation.profile', 'participation.mission')
            ->allowedFilters(
                AllowedFilter::exact('participation.mission.id'),
                AllowedFilter::exact('grade'),
                AllowedFilter::custom('search', new FiltersAvisSearch),
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Request $request, Int $participation_id)
    {
        return Avis::where('participation_id', $participation_id)->first();
    }

    public function store(AvisCreateRequest $request)
    {
        return Avis::create($request->validated());
    }
}
