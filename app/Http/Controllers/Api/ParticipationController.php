<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersParticipationNeedToBeTreated;
use App\Filters\FiltersParticipationSearch;
use App\Filters\FiltersTags;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ParticipationCancelRequest;
use App\Http\Requests\Api\ParticipationCreateRequest;
use App\Http\Requests\Api\ParticipationDeclineRequest;
use App\Http\Requests\Api\ParticipationUpdateRequest;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Models\StructureTag;
use App\Models\Temoignage;
use App\Models\User;
use App\Notifications\ParticipationBenevoleCanceled;
use App\Notifications\ParticipationBenevoleValidated;
use App\Notifications\ParticipationCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Database\Eloquent\Builder;
use App\Filters\FiltersParticipationMissionZip;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Participation::role($request->header('Context-Role'))->with('profile', 'mission'))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch()),
                AllowedFilter::custom('need_to_be_treated', new FiltersParticipationNeedToBeTreated()),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.name'),
                AllowedFilter::exact('mission.department'),
                AllowedFilter::exact('mission.structure.name'),
                AllowedFilter::exact('mission.structure.id'),
                AllowedFilter::exact('mission.template.id'),
                AllowedFilter::exact('profile.id'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('ofTerritoire'),
                AllowedFilter::scope('ofActivity'),
                AllowedFilter::scope('ofDomaine'),
                AllowedFilter::scope('ofResponsable'),
                AllowedFilter::exact('state'),
                AllowedFilter::custom('tags', new FiltersTags()),
                AllowedFilter::custom('mission.zip', new FiltersParticipationMissionZip()),
                AllowedFilter::exact('mission.type'),
                AllowedFilter::exact('id'),
                AllowedFilter::callback('is_state_pending', function (Builder $query, $value) {
                    if($value === true) {
                        $query->whereIn('state', ['En attente de validation', 'En cours de traitement']);
                    }
                })
            )
            ->allowedIncludes([
                'conversation.latestMessage',
                'profile.avatar',
                'mission.responsable',
                'mission.structure',
                'tags'
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Request $request, Participation $participation)
    {
        $participation->load([
            'mission',
            'profile',
            'conversation',
            'conversation.latestMessage',
            'mission.responsable',
            'mission.responsable.user',
            'profile.skills',
            'profile.domaines',
            'profile.avatar',
            'tags'
        ]);

        return $participation;
    }

    public function store(ParticipationCreateRequest $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $mission = Mission::find(request('mission_id'));
        $profile = Profile::find(request('profile_id'));

        if ($mission->responsable_id == $profile->id) {
            abort(422, 'Désolé, vous ne pouvez pas participer à votre propre mission !');
        }

        $participationCount = Participation::where('state', '!=', 'Annulée')->where('profile_id', request('profile_id'))
            ->where('mission_id', request('mission_id'))->count();

        if ($participationCount > 0) {
            abort(422, 'Désolé, vous avez déjà participé à cette mission !');
        }

        $mission = Mission::find(request('mission_id'));

        if($mission->state != 'Validée') {
            abort(422, "Désolé, la mission n'est pas validée");
        }

        if (!$mission->is_registration_open) {
            abort(422, 'Désolé, les inscriptions à cette mission sont fermées !');
        }

        if (!$mission->is_active) {
            abort(422, 'Désolé, cette mission est désactivée !');
        }

        if ($mission && $mission->has_places_left) {
            $participation = Participation::create($request->validated());
            if (request('content')) {
                // En attendant de régler le souci des responsables sans user
                $user = $mission->responsable->user ?? $mission->structure->user;
                $conversation = $currentUser->startConversation($user, $participation);
                $currentUser->sendMessage($conversation->id, request('content'));
                $currentUser->markConversationAsRead($participation->conversation);
            }

            if ($participation->profile) {
                $participation->profile->notify(new ParticipationCreated($participation));
            }

            return $participation;
        }

        abort(422, "Désolé, la mission n'a plus de place disponible !");
    }

    public function update(ParticipationUpdateRequest $request, Participation $participation)
    {
        $participation->update($request->validated());

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation->load(['conversation', 'conversation.latestMessage']);
    }

    public function decline(ParticipationDeclineRequest $request, Participation $participation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        return $currentUser->declineParticipation($participation, $request->input('reason'), $request->input('content'));
    }

    public function cancelByBenevole(ParticipationCancelRequest $request, Participation $participation)
    {
        if ($participation->state == 'Annulée') {
            abort(422, 'La participation a déjà été annulée.');
        }

        $participation->load('conversation');
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if ($participation->conversation) {

            $participation->conversation->messages()->create([
                'from_id' => $currentUser->id,
                'type' => 'contextual',
                'content' => 'La participation a été annulée par ' . $currentUser->profile->full_name,
                'contextual_state' => 'Annulée par bénévole',
                'contextual_reason' => $request->input('reason'),
            ]);

            if ($request->input('content')) {
                $currentUser->sendMessage($participation->conversation->id, $request->input('content'));
                // Trigger updated_at refresh.
                $participation->conversation->touch();
            }

            $currentUser->markConversationAsRead($participation->conversation);

            $participation->mission->responsable->notify(new ParticipationBenevoleCanceled($participation, $request->input('content'), $request->input('reason')));
        }

        // Log (because saveQuietly)
        activity()
           ->causedBy($currentUser)
           ->performedOn($participation)
           ->withProperties([
               'attributes' => ['state' => 'Annulée'],
               'old' => ['state' => $participation->state]
           ])
           ->event('updated')
           ->log('updated');

        $participation->state = 'Annulée';
        $participation->saveQuietly();

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        // Score
        $participation->mission->structure->calculateScore();

        return $participation;
    }

    public function validateByBenevole(Request $request, Participation $participation)
    {
        if ($participation->state == 'Validée') {
            abort(422, 'La participation a déjà été validée.');
        }

        $participation->load('conversation');
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if ($participation->conversation) {
            $participation->conversation->messages()->create([
                'from_id' => $currentUser->id,
                'type' => 'contextual',
                'content' => 'La participation a été validée par ' . $currentUser->profile->full_name,
                'contextual_state' => 'Validée par le bénévole',
            ]);
        }

        if($participation->mission->responsable) {
            $participation->mission->responsable->notify(new ParticipationBenevoleValidated($participation));
        }

        activity()
            ->causedBy($currentUser)
            ->performedOn($participation)
            ->withProperties([
                'attributes' => ['state' => 'Validée'],
                'old' => ['state' => $participation->state]
            ])
            ->event('updated')
            ->log('updated');

        $participation->state = 'Validée';
        $participation->saveQuietly();

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        // Score
        $participation->mission->structure->calculateScore();

        return $participation;
    }

    public function temoignage(Request $request, Participation $participation)
    {
        return [
            'temoignage' => Temoignage::where('participation_id', $participation->id)->first()
        ];
    }

    public function attachStructureTag(Request $request, Participation $participation, StructureTag $structureTag)
    {
        $this->authorize('attachStructureTag', [$participation, $structureTag]);

        $participation->tags()->attach($structureTag);

        return $participation->load('tags');
    }

    public function detachStructureTag(Request $request, Participation $participation, StructureTag $structureTag)
    {
        $this->authorize('detachStructureTag', [$participation, $structureTag]);

        $participation->tags()->detach($structureTag);

        return $participation->load('tags');
    }
}
