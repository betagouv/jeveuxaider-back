<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Http\Requests\Api\MissionCloneRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersMissionCeu;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MissionsExport;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
use App\Http\Requests\Api\MissionDeleteRequest;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Mission::role($request->header('Context-Role'))->with('structure'))
        ->allowedFilters([
            'name',
            'state',
            'format',
            'type',
            'department',
            AllowedFilter::exact('id'),
            AllowedFilter::custom('ceu', new FiltersMissionCeu),
            AllowedFilter::custom('search', new FiltersMissionSearch),
            AllowedFilter::custom('lieu', new FiltersMissionLieu),
            AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
        ])
        ->defaultSort('-updated_at')
        ->paginate(config('query-builder.results_per_page'));
    }

    public function export(Request $request)
    {
        return Excel::download(new MissionsExport($request), 'missions.xlsx');
    }

    public function show(Request $request, Int $id)
    {
        return Mission::with(['structure.members:id,first_name,last_name,mobile,email', 'template.domaine', 'tags'])->where('id', $id)->first();
    }

    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        $mission->update($request->validated());

        if ($request->has('tags')) {
            $mission->syncTagsWithType($request->input('tags'), 'domaine');
        }

        return $mission;
    }

    public function delete(MissionDeleteRequest $request, Mission $mission)
    {
        return (string) $mission->delete();
    }

    public function destroy($id)
    {
        $mission = Mission::withTrashed()->findOrFail($id);
        return (string) $mission->forceDelete();
    }

    public function clone(MissionCloneRequest $request, Mission $mission)
    {
        return $mission->clone();
    }
}
