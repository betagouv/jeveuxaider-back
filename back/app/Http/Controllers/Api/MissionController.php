<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Http\Requests\Api\MissionUpdateRequest;
use App\Http\Requests\Api\MissionStructureRequest;
use App\Http\Requests\Api\MissionCloneRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Filters\FiltersMissionCeu;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MissionsExport;
use App\Filters\FiltersDisponibility;
use App\Filters\FiltersMissionCollectivity;
use App\Filters\FiltersMissionDates;
use App\Filters\FiltersMissionSearch;
use App\Filters\FiltersMissionLieu;
use App\Filters\FiltersMissionPlacesLeft;
use App\Filters\FiltersMissionDomaine;
use App\Filters\FiltersProfileDepartment;
use App\Filters\FiltersProfileSkill;
use App\Filters\FiltersProfileTag;
use App\Filters\FiltersProfileZips;
use App\Http\Requests\Api\MissionDeleteRequest;
use App\Models\Profile;
use App\Models\Structure;
use App\Services\ApiEngagement;

class MissionController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Mission::role($request->header('Context-Role'))->with('structure:id,name,state', 'responsable'))
            ->allowedAppends('domaines')
            ->allowedFilters([
                'name',
                'state',
                'format',
                'type',
                AllowedFilter::exact('department'),
                AllowedFilter::exact('template_id'),
                AllowedFilter::exact('structure_id'),
                AllowedFilter::exact('id'),
                AllowedFilter::custom('ceu', new FiltersMissionCeu),
                AllowedFilter::custom('search', new FiltersMissionSearch),
                AllowedFilter::custom('lieu', new FiltersMissionLieu),
                AllowedFilter::custom('place', new FiltersMissionPlacesLeft),
                AllowedFilter::custom('dates', new FiltersMissionDates),
                AllowedFilter::custom('domaine', new FiltersMissionDomaine),
                AllowedFilter::custom('collectivity', new FiltersMissionCollectivity),
                AllowedFilter::exact('responsable_id'),
            ])
            ->allowedSorts(['places_left', 'type'])
            ->defaultSort('-updated_at')
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }

    public function export(Request $request)
    {
        return Excel::download(new MissionsExport($request), 'missions.xlsx');
    }

    public function show(Request $request, $id)
    {

        if (is_numeric($id)) {
            $mission = Mission::with(['structure.members:id,first_name,last_name,mobile,email', 'template.domaine', 'domaine', 'tags', 'responsable'])->where('id', $id)->first();
        } else {
            // API ENGAGEMENT
            $api = new ApiEngagement();
            $mission = $api->getMission($id);
            if ($mission) {
                $mission['isFromApi'] = true;
            }
        }

        if (!$mission) {
            abort(404, 'Cette mission n\'existe pas');
        }

        return $mission['isFromApi'] ? $mission : $mission->append('participations_total', 'full_url');
    }

    public function update(MissionUpdateRequest $request, Mission $mission)
    {
        if ($request->has('tags')) {
            $mission->syncTagsWithType($request->input('tags'), 'domaine');
        }

        $mission->update($request->validated());

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

    public function structure(MissionStructureRequest $request, Mission $mission)
    {
        return Structure::with('members')->withCount('missions', 'participations', 'waitingParticipations')->where('id', $mission->structure_id)->first();
    }

    public function benevoles(Request $request, Mission $mission)
    {
        if (!$mission->has_places_left || $mission->state != 'Validée') {
            abort(403, "Vous n'êtes pas autorisé à accéder à ce contenu");
        }

        return QueryBuilder::for(Profile::withCount('participations')->where('is_visible', true))
            ->allowedAppends('last_online_at', 'skills', 'domaines')
            ->allowedFilters(
                AllowedFilter::custom('zips', new FiltersProfileZips),
                AllowedFilter::custom('domaine', new FiltersProfileTag),
                AllowedFilter::custom('department', new FiltersProfileDepartment),
                AllowedFilter::custom('disponibilities', new FiltersDisponibility),
                AllowedFilter::custom('skills', new FiltersProfileSkill),
            )
            ->defaultSort('-participations_count')
            ->paginate(config('query-builder.results_per_page'));
    }
}
