<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Young;
use App\Http\Requests\Api\YoungUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\YoungCreateRequest;
use App\Http\Requests\Api\YoungDeleteRequest;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\YoungsExport;
use App\Filters\FiltersYoungSearch;
use App\Http\Requests\YoungRequest;
use Spatie\QueryBuilder\AllowedFilter;
use App\Models\Mission;
use App\Filters\FiltersMissionCeu;
use App\Filters\FiltersYoungContext;
use App\Filters\FiltersYoungLieu;

class YoungController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Young::role($request->header('Context-Role')))
            ->allowedFilters(
                'department',
                'state',
                'mission_format',
                AllowedFilter::custom('search', new FiltersYoungSearch),
                AllowedFilter::custom('lieu', new FiltersYoungLieu),
                AllowedFilter::custom('context', new FiltersYoungContext)
            )
            ->defaultSort('last_name')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function missions(Request $request, Young $young)
    {
        if ($young->regular_latitude && $young->regular_longitude) {
            $missionQueryBuilder = Mission::distance($young->regular_latitude, $young->regular_longitude)->where('state', 'Validée')->orderBy('distance', 'ASC');
        } else {
            $missionQueryBuilder = Mission::where('state', 'Validée');
        }

        return QueryBuilder::for($missionQueryBuilder)
            ->withCount('youngs')
            ->hasPlacesLeft()
            ->allowedFilters(
                'domaines',
                'state',
                'format',
                AllowedFilter::custom('ceu', new FiltersMissionCeu)
            )
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function export(Request $request)
    {
        return Excel::download(new YoungsExport($request), 'youngs.xlsx');
    }

    public function store(YoungCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $user = $request->user();
        $young = Young::create($request->validated());

        return $young;
    }

    public function show(YoungRequest $request, Young $young = null)
    {
        return $young;
    }

    public function update(YoungUpdateRequest $request, Young $young = null)
    {
        $young = $young ?: $request->user()->young;

        $young->update($request->validated());

        return $young;
    }

    public function delete(YoungDeleteRequest $request, Young $young)
    {
        return (string) $young->delete();
    }

    public function destroy($id)
    {
        $young = Young::withTrashed()->findOrFail($id);
        return (string) $young->forceDelete();
    }
}
