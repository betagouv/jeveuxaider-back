<?php

namespace App\Http\Controllers\Api;

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
        return QueryBuilder::for(Avis::role($request->header('Context-Role'))->with('profile', 'mission', 'mission.structure:id,name,state', 'mission.responsable'))
            ->allowedFilters(
                AllowedFilter::exact('mission.department'),
                'mission.type',
                'mission.name',
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('profile.id'),
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
