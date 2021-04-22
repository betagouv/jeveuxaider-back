<?php

namespace App\Http\Controllers\Api;

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
use App\Exports\StructuresExport;
use App\Filters\FiltersStructureCollectivity;
use App\Filters\FiltersStructureIsCollectivity;
use Illuminate\Support\Facades\Auth;
use App\Filters\FiltersStructureLieu;
use App\Filters\FiltersStructureSearch;
use App\Http\Requests\StructureRequest;
use App\Jobs\NotifyUserOfCompletedExport;
// use App\Jobs\ProcessExportStructures;
use App\Models\Mission;
use App\Models\Tag;
use Illuminate\Support\Str;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Structure::role($request->header('Context-Role'))
            ->withCount('missions', 'participations', 'waitingParticipations'))
            ->allowedFilters([
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                AllowedFilter::custom('ceu', new FiltersStructureCeu),
                AllowedFilter::custom('lieu', new FiltersStructureLieu),
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::custom('collectivity', new FiltersStructureCollectivity),
                AllowedFilter::custom('is_collectivity', new FiltersStructureIsCollectivity),
            ])
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    // LARAVEL EXCEL
    public function export(Request $request)
    {
        $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
        $fileName = 'organisations-' . Str::random(8) . '.csv';
        $filePath = $folder . $fileName;

        (new StructuresExport($request->header('Context-Role')))
            ->queue($filePath, 's3')
            ->chain([
                new NotifyUserOfCompletedExport($request->user(), $filePath),
            ]);

        return response()->json(['message'=> 'Export en cours...'], 200);
    }

    // FAST EXCEL
    // PB: serializer la query avant le job https://github.com/AnourValar/eloquent-serialize
    // public function export(Request $request)
    // {
    //     $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
    //     $fileName = 'organisations-' . Str::random(8) . '.xlsx';
    //     $filePath = $folder . $fileName;

    //     ProcessExportStructures::withChain([
    //         new NotifyUserOfCompletedExport($request->user(), $filePath),
    //     ])->dispatch($request->user(), $request->header('Context-Role'), $filePath, $fileName);

    //     return response()->json(['message'=> 'Export en cours...'], 200);
    // }

    public function availableMissions(Request $request, Structure $structure)
    {
        $query = QueryBuilder::for(Mission::with('domaine'))
            ->allowedAppends(['domaines'])
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
        return Structure::with('members')->withCount('missions', 'participations', 'waitingParticipations')->where('id', $structure->id)->first();
    }

    public function store(StructureCreateRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $structure = Structure::create(
            array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id])
        );

        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $structure->syncTagsWithType($domaines, 'domaine');
        }

        return $structure;
    }

    public function update(StructureUpdateRequest $request, Structure $structure)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        if ($request->has('domaines')) {
            $domaines_ids = collect($request->input('domaines'))->pluck('id');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $structure->syncTagsWithType($domaines, 'domaine');
        }

        $structure->update($request->validated());

        return Structure::with('members')->withCount('missions')->where('id', $structure->id)->first();
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

    public function invitations(StructureRequest $request, Structure $structure)
    {
        return $structure->invitations;
    }

    public function addMember(StructureInvitationRequest $request, Structure $structure)
    {
        $profile = Profile::where('email', 'ILIKE', request('email'))->first();
        $user = $request->user();
        $role = request('role');

        if ($structure->members()->where('email', 'ILIKE', request('email'))->first()) {
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
