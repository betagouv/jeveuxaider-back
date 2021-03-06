<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersStructureSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddResponsableRequest;
use App\Http\Requests\Api\MissionCreateRequest;
use App\Http\Requests\Api\StructureCreateRequest;
use App\Http\Requests\Api\StructureDeleteRequest;
use App\Http\Requests\Api\StructureUpdateRequest;
use App\Http\Requests\StructureRequest;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Services\ApiEngagement;
use App\Sorts\StructureMissionsCountSort;
use App\Sorts\StructurePlacesLeftSort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Structure::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch),
                AllowedFilter::exact('department'),
                'state',
                'statut_juridique',
                AllowedFilter::exact('reseaux.id'),
                AllowedFilter::exact('reseaux.name'),
                AllowedFilter::scope('ofReseau'),
            ])
            ->allowedIncludes([
                'domaines',
                'reseaux',
                'illustrations',
                'overrideImage1',
                AllowedInclude::count('missionsCount'),
            ])
            ->defaultSort('-created_at')
            ->allowedSorts([
                'created_at',
                'updated_at',
                AllowedSort::custom('missions_count', new StructureMissionsCountSort()),
                AllowedSort::custom('places_left', new StructurePlacesLeftSort()),
            ])
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));

        if ($request->has('append')) {
            $results->append($request->input('append'));
        }

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

        return $structure->load(['territoire', 'members.tags', 'domaines', 'reseaux', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2'])->append(['missing_fields', 'completion_rate']);
    }

    public function associationSlugOrId(Request $request, $slugOrId)
    {
        $query = (is_numeric($slugOrId)) ? Structure::where('id', $slugOrId) : Structure::where('slug', $slugOrId);
        $structure = $query->where('state', 'Valid??e')
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
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'structure_domaines'];
            });
            $structure->domaines()->sync($values);
        }

        return $structure;
    }

    public function update(StructureUpdateRequest $request, Structure $structure)
    {
        if ($request->has('domaines')) {
            $domaines = collect($request->input('domaines'));
            $values = $domaines->pluck($domaines, 'id')->map(function ($item) {
                return ['field' => 'structure_domaines'];
            });
            $structure->domaines()->sync($values);
        }

        if ($request->has('reseaux')) {
            if ($request->input('reseaux')) {
                //  $structure->reseaux()->syncWithoutDetaching([$request->input('tete_de_reseau_id')]);
                $reseaux = collect($request->input('reseaux'));
                $structure->reseaux()->sync($reseaux->pluck('id'));
            }
        }

        if ($request->has('illustrations')) {
            $illustrations = collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'organisation_illustrations'];
            });
            $structure->illustrations()->sync($values);
        }

        $structure->update($request->validated());

        return $structure;
    }

    public function unregister(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('unregister', $structure)) {
            abort(403, "Vous n'avez pas les droits n??c??ssaires pour r??aliser cette action");
        }

        $structure->update([
            'state' => 'D??sinscrite',
        ]);

        return $structure;
    }

    public function waitingParticipations(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits n??c??ssaires pour r??aliser cette action");
        }

        return Participation::ofStructure($structure->id)->ofResponsable(Auth::guard('api')->user()->profile->id)->where('state', 'En attente de validation')->count();
    }

    public function validateWaitingParticipations(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits n??c??ssaires pour r??aliser cette action");
        }

        Participation::with(['profile', 'mission', 'mission.structure', 'conversation'])->role('responsable')->ofResponsable(Auth::guard('api')->user()->profile->id)->where('state', 'En attente de validation')->chunk(50, function ($collection) {
            $collection->map(function ($participation) {
                $participation->update(['state' => 'Valid??e']);
            });
        });

        return true;
    }

    public function deleteMember(StructureRequest $request, Structure $structure, Profile $member)
    {
        $structure->deleteMember($member);

        return $structure->members;
    }

    public function addMission(MissionCreateRequest $request, Structure $structure)
    {
        $attributes = array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id]);

        if ($structure->state != 'Valid??e' && empty($attributes['state'])) {
            $attributes['state'] = 'Brouillon';
        }

        $mission = $structure->addMission($attributes);

        if ($request->has('skills')) {
            $skills = collect($request->input('skills'));
            $values = $skills->pluck($skills, 'id')->map(function ($item) {
                return ['field' => 'mission_skills'];
            });
            $mission->skills()->sync($values);
        }

        if ($request->has('illustrations')) {
            $illustrations = collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'mission_illustrations'];
            });
            $mission->illustrations()->sync($values);
        }

        return $mission;
    }

    public function exist(Request $request, $rnaOrName)
    {
        $structure = Structure::whereIn('state', ['En attente de validation', 'Valid??e'])
            ->where(function ($query) use ($rnaOrName) {
                $query->where('api_id', '=', $rnaOrName)
                    ->orWhere('name', 'ILIKE', $rnaOrName);
            })
            ->orWhere(function ($query) use ($rnaOrName) {
                $query->whereHas('territoire', function ($query) use ($rnaOrName) {
                    $query
                        ->whereIn('state', ['waiting', 'validated'])
                        ->where('name', 'ILIKE', $rnaOrName);
                });
            })
            ->first();

        if ($structure === null) {
            return false;
        }

        return [
            'structure_id' => $structure->id,
            'structure_name' => $structure->name,
            'responsable_fullname' => $structure->responsables->first() ? $structure->responsables->first()->full_name : null,
        ];
    }

    public function delete(StructureDeleteRequest $request, Structure $structure)
    {
        $relatedMissionsCount = Mission::where('structure_id', $structure->id)->count();

        if ($relatedMissionsCount) {
            abort('422', "Cette organisation est reli??e ?? {$relatedMissionsCount} mission(s)");
        }

        $structure->responsables->map(function ($responsable) use ($structure) {
            $structure->deleteMember($responsable);
        });

        return (string) $structure->delete();
    }

    public function responsables(Request $request, Structure $structure)
    {
        return $structure->responsables()->get();
    }

    public function addResponsable(AddResponsableRequest $request, Structure $structure)
    {
        $profile = Profile::whereEmail($request->input('email'))->first();

        if ($profile && $profile->structures->count() > 0) {
            abort(422, 'Cet email est d??j?? rattach?? ?? une organisation');
        }

        $structure->addMember($profile, 'responsable');
        $profile->user->resetContextRole();

        return $structure->members;
    }
}
