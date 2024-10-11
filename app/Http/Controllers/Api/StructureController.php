<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersStructureSearch;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddResponsableRequest;
use App\Http\Requests\Api\StructureCreateRequest;
use App\Http\Requests\Api\StructureDeleteRequest;
use App\Http\Requests\Api\StructureUpdateRequest;
use App\Http\Requests\StructureRequest;
use App\Jobs\RecomputeConversationUsersWhenMissionResponsablesAdded;
use App\Jobs\RecomputeConversationUsersWhenMissionResponsablesRemoved;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\StructureAskUnregister;
use App\Notifications\StructureSwitchResponsable;
use App\Services\ApiEngagement;
use App\Sorts\StructureMissionsCountSort;
use App\Sorts\StructurePlacesLeftSort;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedInclude;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Notification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class StructureController extends Controller
{
    public function index(Request $request)
    {
        $results = QueryBuilder::for(Structure::role($request->header('Context-Role')))
            ->allowedFilters([
                AllowedFilter::custom('search', new FiltersStructureSearch()),
                AllowedFilter::exact('department'),
                AllowedFilter::exact('state'),
                AllowedFilter::exact('statut_juridique'),
                AllowedFilter::exact('reseaux.id'),
                AllowedFilter::exact('reseaux.name'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::callback('exclude', function (Builder $query, $value) {
                    if(is_numeric($value)) {
                        $query->where('id', '!=', $value);
                    }
                })
            ])
            ->allowedIncludes([
                'domaines',
                'reseaux',
                'illustrations',
                'overrideImage1',
                AllowedInclude::count('missionsCount'),
                AllowedInclude::count('membersCount'),
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
            $results->append(explode(',', $request->input('append')));
        }

        return $results;
    }

    // public function availableMissions(Request $request, Structure $structure)
    // {
    //     $query = QueryBuilder::for(Mission::with(['domaine', 'template', 'template.domaine', 'template.photo', 'illustrations', 'structure']))
    //         ->available()
    //         ->where('structure_id', $structure->id);

    //     if ($request->has('exclude')) {
    //         $query->where('id', '<>', $request->input('exclude'));
    //     }

    //     return $query
    //         ->defaultSort('-updated_at')
    //         ->allowedSorts(['places_left', 'type'])
    //         ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    // }

    public function show(Request $request, Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('view', $structure)) {
            abort(403);
        }

        return $structure
            ->load(['territoire', 'members.profile.tags', 'members.profile.user', 'domaines', 'reseaux', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2'])
            ->append(['missing_fields', 'completion_rate', 'permissions']);
    }

    public function activities(Request $request, Structure $structure)
    {
        $results = DB::select(
            "
                SELECT activities.id, activities.name, COUNT(*) FROM activities
                LEFT JOIN missions ON missions.activity_id = activities.id OR missions.activity_secondary_id = activities.id
                WHERE missions.structure_id = :structure
                AND missions.deleted_at IS NULL
                AND missions.state IN ('Validée', 'Terminée')
                GROUP BY activities.id
                ORDER BY COUNT(*) DESC
            ",
            [
                'structure' => $structure->id,
            ]
        );

        return $results;
    }

    public function associationSlugOrId(Request $request, $slugOrId)
    {
        $query = (is_numeric($slugOrId)) ? Structure::where('id', $slugOrId) : Structure::where('slug', $slugOrId);
        $structure = $query->where('state', 'Validée')
            ->where('statut_juridique', 'Association')
            ->firstOrFail();

        if ($structure) {
            $structure->load(['domaines', 'logo', 'illustrations', 'overrideImage1', 'overrideImage2']);
            $structure->loadCount(['missionsAvailable', 'participations']);
            $structure->append(['places_left', 'statistics']);
        }

        return $structure;
    }

    public function store(StructureCreateRequest $request)
    {
        $user = Auth::guard('api')->user();
        $structureAttributes = [
            'user_id' => $user->id,
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
            $reseaux = collect($request->input('reseaux'));
            $values = $reseaux->pluck($reseaux, 'id')->toArray();
            $structure->reseaux()->sync(array_keys($values));
        }

        if ($request->has('illustrations')) {
            $illustrations = collect($request->input('illustrations'));
            $values = $illustrations->pluck($illustrations, 'id')->map(function ($item) {
                return ['field' => 'organisation_illustrations'];
            });
            $structure->illustrations()->sync($values);
        }

        if ($request->has('responsable_fonction')) {
            $structure->members()->updateExistingPivot($structure->user, [
                'fonction' => $request->input('responsable_fonction'),
            ]);
        }

        $structure->update($request->validated());

        return $structure;
    }

    public function askToUnregister(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('unregister', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $user = User::find(Auth::guard('api')->user()->id);

        Notification::route('mail', ['sophie.galent@beta.gouv.fr', 'caroline.farhi@beta.gouv.fr'])
            ->route('slack', config('services.slack.hook_url'))
            ->notify(new StructureAskUnregister($user, $structure));

        return $structure;
    }

    public function unregister(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('unregister', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        $members = $structure->members;

        $structure->update([
            'state' => 'Désinscrite',
        ]);

        $members->each(function ($user) use ($structure) {
            $user->notify(new \App\Notifications\StructureUnsubscribed($structure));
        });

        return $structure;
    }


    public function status(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return [
            'responsables' => $structure->members,
            'participations_count' => Participation::ofStructure($structure->id)->count()
        ];
    }

    // @todo: Plus utilisé ?
    // public function waitingParticipations(Structure $structure)
    // {
    //     if (Auth::guard('api')->user()->cannot('update', $structure)) {
    //         abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
    //     }

    //     return Participation::ofStructure($structure->id)->ofResponsable(Auth::guard('api')->user()->profile->id)->where('state', 'En attente de validation')->count();
    // }

    public function validateWaitingParticipations(Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        Participation::with(['profile', 'mission', 'mission.structure', 'conversation'])->role('responsable')->ofResponsable(Auth::guard('api')->user()->profile->id)->where('state', 'En attente de validation')->chunk(50, function ($collection) {
            $collection->map(function ($participation) {
                $participation->update(['state' => 'Validée']);
            });
        });

        return true;
    }

    public function deleteMember(StructureRequest $request, Structure $structure, User $user)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        // Switch responsable
        if ($request->has('new_responsable_id') && $request->input('new_responsable_id')) {
            $newResponsable = Profile::find($request->input('new_responsable_id'));
            if ($newResponsable) {
                Mission::ofResponsable($user->profile->id)
                    ->where('structure_id', $structure->id)
                    ->get()->map(function ($mission) use ($user, $newResponsable) {
                        // Remove from conversations
                        RecomputeConversationUsersWhenMissionResponsablesRemoved::dispatch($mission, [$user->id]);
                        $mission->responsables()->detach($user->profile->id);
                        // Add to conversations
                        RecomputeConversationUsersWhenMissionResponsablesAdded::dispatch($mission, [$newResponsable->user_id]);
                        $mission->responsables()->syncWithoutDetaching([$newResponsable->id]);
                    });
                if ($currentUser->profile->id != $newResponsable->id) {
                    $newResponsable->notify(new StructureSwitchResponsable($structure, $user->profile));
                }
            }
        }

        $structure->deleteMember($user);

        return $structure->members;
    }

    public function exist(Request $request, $rnaOrName)
    {
        $structure = Structure::whereIn('state', ['En attente de validation', 'Validée', 'En cours de traitement'])
            ->where(function ($query) use ($rnaOrName) {
                $query->where(function ($query) use ($rnaOrName) {
                    $query->where('api_id', '=', $rnaOrName)
                        ->orWhere('name', 'ILIKE', $rnaOrName);
                })
                ->orWhere(function ($query) use ($rnaOrName) {
                    $query->whereHas('territoire', function ($query) use ($rnaOrName) {
                        $query
                            ->whereIn('state', ['waiting', 'validated'])
                            ->where('name', 'ILIKE', $rnaOrName);
                    });
                });
            })
            ->first();

        if ($structure === null) {
            return ['structure' => null];
        }

        return [
            'structure' => [
                'structure_id' => $structure->id,
                'structure_name' => $structure->name,
                'responsable_fullname' => $structure->members->first() ? $structure->members->first()->profile->full_name : null,
            ]
        ];
    }

    public function delete(StructureDeleteRequest $request, Structure $structure)
    {
        $relatedMissionsCount = Mission::where('structure_id', $structure->id)->count();

        if ($relatedMissionsCount) {
            abort('422', "Cette organisation est reliée à {$relatedMissionsCount} mission(s)");
        }

        $structure->members->map(function ($user) use ($structure) {
            $structure->deleteMember($user);
        });

        return (string) $structure->delete();
    }

    public function responsables(Request $request, Structure $structure)
    {
        if (Auth::guard('api')->user()->cannot('update', $structure)) {
            abort(403, "Vous n'avez pas les droits nécéssaires pour réaliser cette action");
        }

        return $structure->members()->with(['profile' => function ($query) {
            $query->withCount('missions');
        }, 'profile.tags'])->get();
    }

    public function addResponsable(AddResponsableRequest $request, Structure $structure)
    {
        $user = User::whereEmail($request->input('email'))->first();

        if ($user && $user->structures->count() > 0) {
            abort(422, 'Cet email est déjà rattaché à une organisation');
        }

        $structure->addMember($user);

        return $structure->members;
    }

    public function score(Request $request, Structure $structure)
    {
        return $structure->score;
    }

    public function popular(Request $request)
    {
        return ['organisations' => DB::select("
            SELECT COUNT(participations) as participations_count, structures.name
            FROM participations
            LEFT JOIN missions ON missions.id = participations.mission_id
            LEFT JOIN structures ON structures.id = missions.structure_id
            WHERE participations.created_at > NOW() - INTERVAL '30 days'
            AND missions.state = 'Validée'
            AND participations.state = 'Validée'
            GROUP BY structures.name
            ORDER BY COUNT(participations) DESC
            LIMIT 20
        ")];
    }

    public function invitations(Request $request, Structure $structure)
    {
        $this->authorize('viewInvitations', $structure);

        return $structure->invitations()->with('user.profile')->orderBy('id')->get();
    }


}
