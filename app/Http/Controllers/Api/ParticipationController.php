<?php

namespace App\Http\Controllers\API;

use App\Exports\ParticipationsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersParticipationLieu;
use App\Http\Requests\Api\ParticipationCreateRequest;
use App\Http\Requests\Api\ParticipationUpdateRequest;
use Spatie\QueryBuilder\AllowedFilter;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Participation::role($request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::custom('lieu', new FiltersParticipationLieu),
                'state',
                'mission.department',
                'mission.type',
                'mission.name'
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'))
            ;
    }

    public function export(Request $request)
    {
        return Excel::download(new ParticipationsExport($request), 'profiles.xlsx');
    }

    public function store(ParticipationCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $user = $request->user();
        $participation = Participation::create($request->validated());

        return $participation;
    }

    public function update(ParticipationUpdateRequest $request, Participation $participation)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $participation->update($request->validated());

        return $participation;
    }
}
