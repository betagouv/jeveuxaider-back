<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Structure;
use App\Http\Requests\Api\StructureCreateRequest;
use App\Http\Requests\Api\StructureUpdateRequest;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\Api\MissionCreateRequest;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Support\Facades\Auth;
use App\Filters\FiltersStructureSearch;
use App\Models\Mission;
use App\Models\Tag;
use App\Services\ApiEngagement;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Structure::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                // AllowedFilter::custom('rna', new FiltersStructureWithRna),
                AllowedFilter::scope('ofReseau'),
            ])
            ->allowedIncludes([
                'domaines'
            ])
            ->allowedAppends([
                'places_left',
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    // public function export(Request $request)
    // {
    //     $folder = 'public/'. config('app.env').'/exports/'.$request->user()->id . '/';
    //     $fileName = 'organisations-' . Str::random(8) . '.csv';
    //     $filePath = $folder . $fileName;

    //     (new StructuresExport($request->header('Context-Role')))
    //         ->queue($filePath, 's3')
    //         ->chain([
    //             new NotifyUserOfCompletedExport($request->user(), $filePath),
    //         ]);

    //     return response()->json(['message'=> 'Export en cours...'], 200);
    // }

    public function availableMissions(Request $request, Structure $structure)
    {

        $query = QueryBuilder::for(Mission::with([ 'domaine', 'template', 'template.domaine', 'template.media', 'structure']))
        //->allowedAppends(['domaines'])
        ->available()
        ->where('structure_id', $structure->id);

        if ($request->has('exclude')) {
            $query->where('id', '<>', $request->input('exclude'));
        }

        return $query
            ->defaultSort('-updated_at')
            ->allowedSorts(['places_left', 'type'])
            ->paginate($request->input('itemsPerPage') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('view', $structure)) {
            abort(403);
        }

        return $structure->load(['territoire', 'members', 'domaines'])->append(['full_address']);
    }

    public function associationSlugOrId(Request $request, $slugOrId)
    {
        $query = (is_numeric($slugOrId)) ? Structure::where('id', $slugOrId) : Structure::where('slug', $slugOrId);
        $structure = $query->where('state', 'Validée')
                        ->where('statut_juridique', 'Association')
                        ->first();

        if ($structure) {
            $structure->load(['domaines']);
            $structure->append(['places_left', 'full_address']);
        }

        return $structure;
    }

    public function store(StructureCreateRequest $request)
    {
        // if (!$request->validated()) {
        //     return $request->validated();
        // }

        $structureAttributes = [
            'user_id' => Auth::guard('api')->user()->id,
        ];

        // MAPPING API ENGAGEMENT
        if ($request->has('structure_api') && $request->input('structure_api')) {
            $structureAttributes = array_merge(
                $structureAttributes,
                ApiEngagement::prepareStructureAttributes($request->input('structure_api'))
            );
        }

        $structure = Structure::create(
            array_merge($request->validated(), $structureAttributes)
        );

        if ($request->has('domaines')) {
            $domaines_ids = $request->input('domaines');
            $domaines = Tag::whereIn('id', $domaines_ids)->get();
            $structure->syncTagsWithType($domaines, 'domaine');
        }

        return $structure;
    }

    public function update(StructureUpdateRequest $request, Structure $structure)
    {
        // if (!$request->validated()) {
        //     return $request->validated();
        // }

        if ($request->has('domaines')) {
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'structure_domaines'];
            });
            $structure->domaines()->sync($values);
        }

        if ($request->has('tete_de_reseau_id')) {
            if ($request->input('tete_de_reseau_id')) {
                $structure->reseaux()->syncWithoutDetaching([$request->input('tete_de_reseau_id')]);
            }
        }

        $structure->update($request->validated());

        return $structure;

        //return Structure::with(['members'])->withCount('missions')->where('id', $structure->id)->first()->append('completion_rate');
    }

    public function unregister(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('unregister', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $structure->update([
            'state' => 'Désinscrite'
        ]);

        return $structure;
    }

    // public function delete(StructureDeleteRequest $request, Structure $structure)
    // {
    //     if ($structure->missions()->exists()) {
    //         return response()->json(['errors'=> [
    //             'password' => [
    //                 "L'organisation ne peut pas être supprimée car elle a des missions liées.",
    //             ]
    //         ]], 400);
    //     }

    //     if ($structure->territoire) {
    //         $structure->territoire->update(['structure_id' => null]);
    //     }

    //     return (string) $structure->delete();
    // }

    // public function restore($id)
    // {
    //     $structure = Structure::withTrashed()->findOrFail($id);
    //     $this->authorize('restore', $structure);
    //     return (string) $structure->restore();
    // }

    // public function destroy($id)
    // {
    //     $structure = Structure::withTrashed()->findOrFail($id);
    //     $this->authorize('destroy', $structure);
    //     return (string) $structure->forceDelete();
    // }

    // public function members(StructureRequest $request, Structure $structure)
    // {
    //     return $structure->members;
    // }

    // public function reseaux(StructureRequest $request, Structure $structure)
    // {
    //     return $structure->reseaux;
    // }

    // public function invitations(StructureRequest $request, Structure $structure)
    // {
    //     return $structure->invitations;
    // }

    // public function addMember(StructureInvitationRequest $request, Structure $structure)
    // {
    //     $profile = Profile::where('email', 'ILIKE', request('email'))->first();
    //     $user = $request->user();
    //     $role = request('role');

    //     if ($structure->members()->where('email', 'ILIKE', request('email'))->first()) {
    //         return response()->json(['errors' => [
    //             'members' => ['Ce profil appartient déjà à l\'équipe']
    //         ]], 422);
    //     }

    //     if (!$profile) {
    //         $profile = Profile::create($request->validated());
    //     }

    //     $structure->addMember($profile, $role);
    //     $profile->notify(new StructureInvitationSent($structure, $user, $role));

    //     return $structure->members;
    // }

    // public function deleteMember(StructureRequest $request, Structure $structure, Profile $member)
    // {
    //     $structure->deleteMember($member);
    //     return $structure->members;
    // }

    public function addMission(MissionCreateRequest $request, Structure $structure)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id]);

        if ($structure->state != 'Validée' && empty($attributes['state'])) {
            $attributes['state'] = 'Brouillon';
        }

        $mission = $structure->addMission($attributes);

        return $mission;
    }

    // public function upload(StructureUploadRequest $request, Structure $structure, String $field)
    // {

    //     // Delete previous file
    //     if ($media = $structure->getFirstMedia('structures', ['field' => $field])) {
    //         $media->delete();
    //     }

    //     $data = $request->all();
    //     $extension = $request->file('image')->guessExtension();
    //     $name = Str::random(30);

    //     $cropSettings = json_decode($data['cropSettings']);
    //     if (!empty($cropSettings)) {
    //         $stringCropSettings = implode(",", [
    //             $cropSettings->width,
    //             $cropSettings->height,
    //             $cropSettings->x,
    //             $cropSettings->y
    //         ]);
    //     } else {
    //         $pathName = $request->file('image')->getPathname();
    //         $infos = getimagesize($pathName);
    //         $stringCropSettings = implode(",", [
    //             $infos[0],
    //             $infos[1],
    //             0,
    //             0
    //         ]);
    //     }

    //     $structure
    //         ->addMedia($request->file('image'))
    //         ->usingName($name)
    //         ->usingFileName($name . '.' . $extension)
    //         ->withCustomProperties(['field' => $field])
    //         ->withManipulations([
    //             'thumb' => ['manualCrop' => $stringCropSettings],
    //             'large' => ['manualCrop' => $stringCropSettings],
    //             'xxl' => ['manualCrop' => $stringCropSettings]
    //         ])
    //         ->toMediaCollection('structures');

    //     return $structure;
    // }

    // public function uploadDelete(StructureUploadRequest $request, Structure $structure, String $field)
    // {
    //     if ($media = $structure->getFirstMedia('structures', ['field' => $field])) {
    //         $media->delete();
    //     }
    // }

    // public function pushApiEngagement(Request $request, Structure $structure)
    // {
    //     if ($structure && $structure->canBeSendToApiEngagement()) {
    //         return (new ApiEngagement())->syncAssociation($structure);
    //     }
    // }

    public function exist(Request $request, $apiId)
    {
        $structure = Structure::where('api_id', '=', $apiId)
            ->orWhere('name', 'ILIKE', $apiId)
            ->first();

        if ($structure === null) {
            return false;
        }

        return [
            'structure_name' => $structure->name,
            'responsable_fullname' => $structure->responsables->first() ? $structure->responsables->first()->full_name : null
        ];
    }

    // public function attachReseaux(Request $request, Structure $structure)
    // {
    //     if ($request->input('reseaux')) {
    //         $structure->reseaux()->syncWithoutDetaching($request->input('reseaux'));
    //     }

    //     return $structure;
    // }

    // public function detachReseau(Request $request, Structure $structure, Reseau $reseau)
    // {
    //     $structure->reseaux()->detach($reseau->id);

    //     return $structure;
    // }
}
