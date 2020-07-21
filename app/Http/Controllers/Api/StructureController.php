<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Http\Requests\Api\StructureCreateRequest;
use App\Http\Requests\Api\StructureUpdateRequest;
use App\Http\Requests\Api\StructureDeleteRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Models\Profile;
use App\Http\Requests\Api\MissionCreateRequest;
use App\Http\Requests\StructureInvitationRequest;
use App\Notifications\StructureInvitationSent;
use App\Filters\FiltersStructureCeu;
use Spatie\QueryBuilder\AllowedFilter;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StructuresExport;
use Illuminate\Support\Facades\Auth;
use App\Filters\FiltersStructureLieu;
use App\Filters\FiltersStructureSearch;
use App\Http\Requests\StructureRequest;
use App\Models\Mission;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Structure::role($request->header('Context-Role'))->with('members')->withCount('missions'))
            ->allowedAppends('response_ratio')
            ->allowedFilters([
                'department',
                'state',
                'statut_juridique',
                AllowedFilter::custom('ceu', new FiltersStructureCeu),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
            ])
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function export(Request $request)
    {
        return Excel::download(new StructuresExport($request), 'structures.xlsx');
    }

    public function availableMissions(Request $request, Structure $structure)
    {
        $query = QueryBuilder::for(Mission::with('domaine'))
            ->available()
            ->where('structure_id', $structure->id);

        if ($request->has('exclude')) {
            $query->where('id', '<>', $request->input('exclude'));
        }

        return $query
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(StructureRequest $request, Structure $structure)
    {
        return Structure::with('members')->withCount('missions')->where('id', $structure->id)->first()->append('response_ratio');
    }

    public function store(StructureCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $structure = Structure::create(
            array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id])
        );

        return $structure;
    }

    public function update(StructureUpdateRequest $request, Structure $structure)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $structure->update($request->validated());

        return Structure::with('members')->withCount('missions')->where('id', $structure->id)->first();
        ;
    }

    public function delete(StructureDeleteRequest $request, Structure $structure)
    {
        return (string) $structure->delete();
    }

    public function destroy($id)
    {
        $structure = Structure::withTrashed()->findOrFail($id);
        return (string) $structure->forceDelete();
    }

    public function members(StructureRequest $request, Structure $structure)
    {
        return $structure->members;
    }

    public function addMember(StructureInvitationRequest $request, Structure $structure)
    {
        $profile = Profile::whereEmail(request('email'))->first();
        $user = $request->user();
        $role = request('role');

        if ($structure->members()->whereEmail(request('email'))->first()) {
            return response()->json(['errors' => [
                'members' => ['Ce profil appartient déjà à l\'équipe']
            ]], 422);
        }

        if (!$profile) {
            $profile = Profile::create($request->validated());
        }

        $structure->addMember($profile, $role);
        $profile->notify(new StructureInvitationSent($structure, $user, $role));

        return $structure->members;
    }

    public function deleteMember(StructureRequest $request, Structure $structure, Profile $member)
    {
        $structure->deleteMember($member);
        return $structure->members;
    }

    public function addMission(MissionCreateRequest $request, Structure $structure)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id]);

        if ($structure->state != 'Validée') {
            $attributes['state'] = 'En attente de validation';
        }

        $mission = $structure->addMission($attributes);

        return $mission;
    }
}
