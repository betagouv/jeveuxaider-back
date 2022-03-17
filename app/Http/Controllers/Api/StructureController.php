<?php

namespace App\Http\Controllers\Api;

use App\Exports\StructuresExport;
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
use App\Http\Requests\StructureRequest;
use App\Jobs\NotifyUserOfCompletedExport;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Tag;
use App\Services\ApiEngagement;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedInclude;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Structure::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('reseaux.id'),
                AllowedFilter::exact('reseaux.name'),
                'state',
                'statut_juridique',
                // AllowedFilter::custom('rna', new FiltersStructureWithRna),
                // AllowedFilter::scope('ofReseau'),
            ])
            ->allowedIncludes([
                'domaines',
                'illustrations',
                'overrideImage1',
                AllowedInclude::count('missionsCount')
            ])
            ->defaultSort('-created_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        $results->append(['places_left']);

        return $results;
    }

    public function availableMissions(Request $request, Structure $structure)
    {

        $query = QueryBuilder::for(Mission::with(['domaine', 'template', 'template.domaine', 'template.photo', 'illustrations', 'structure']))
            ->available()
            ->where('structure_id', $structure->id);

        if ($request->has('exclude')) {
            $query->where('id', '<>', $request->input('exclude'));
        }

        return $query
            ->defaultSort('-updated_at')
            ->allowedSorts(['places_left', 'type'])
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function show(Request $request, Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('view', $structure)) {
            abort(403);
        }

        return $structure->load(['territoire', 'members', 'domaines', 'reseaux', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2'])->append(['missing_fields', 'completion_rate']);
    }

    public function associationSlugOrId(Request $request, $slugOrId)
    {
        $query = (is_numeric($slugOrId)) ? Structure::where('id', $slugOrId) : Structure::where('slug', $slugOrId);
        $structure = $query->where('state', 'Validée')
            ->where('statut_juridique', 'Association')
            ->first();

        if ($structure) {
            $structure->load(['domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2']);
            $structure->append(['places_left']);
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
            $domaines =  collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'structure_domaines'];
            });
            $structure->domaines()->sync($values);
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

        if ($request->has('reseaux')) {
            if ($request->input('reseaux')) {
                //  $structure->reseaux()->syncWithoutDetaching([$request->input('tete_de_reseau_id')]);
                $reseaux =  collect($request->input('reseaux'));
                $structure->reseaux()->sync($reseaux->pluck('id'));
            }
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'organisation_illustrations'];
            });
            $structure->illustrations()->sync($values);
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

    public function waitingParticipations(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return Participation::ofStructure($structure->id)->where('state','En attente de validation')->count();
    }

    public function validateWaitingParticipations(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        Participation::with(['profile','mission','mission.structure','conversation'])->role('responsable')->where('state', 'En attente de validation')->chunk(50, function($collection){
            $collection->map(function ($participation) {
                $participation->update(['state' => 'Validée']);
            });
        });

        return true;
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

    public function deleteMember(StructureRequest $request, Structure $structure, Profile $member)
    {
        $structure->deleteMember($member);
        return $structure->members;
    }

    public function addMission(MissionCreateRequest $request, Structure $structure)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id]);

        if ($structure->state != 'Validée' && empty($attributes['state'])) {
            $attributes['state'] = 'Brouillon';
        }

        $mission = $structure->addMission($attributes);

        if ($request->has('skills')) {
            $skills =  collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'mission_skills'];
            });
            $mission->skills()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations =  collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'mission_illustrations'];
            });
            $mission->illustrations()->sync($values);
        }

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
