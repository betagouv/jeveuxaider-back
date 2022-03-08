<?php

namespace App\Http\Controllers\Api;

use App\Exports\ParticipationsExport;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Participation;
use Spatie\QueryBuilder\QueryBuilder;
use Maatwebsite\Excel\Facades\Excel;
use App\Filters\FiltersParticipationSearch;
use App\Http\Requests\Api\ParticipationCancelRequest;
use App\Http\Requests\Api\ParticipationCreateRequest;
use App\Http\Requests\Api\ParticipationUpdateRequest;
use App\Http\Requests\Api\ParticipationDeleteRequest;
use App\Http\Requests\Api\ParticipationDeclineRequest;
use App\Http\Requests\Api\ParticipationManageRequest;
use App\Models\Conversation;
use App\Models\Mission;
use App\Models\Temoignage;
use App\Models\User;
use App\Notifications\ParticipationBenevoleCanceled;
use App\Notifications\ParticipationDeclined;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(Participation::role($request->header('Context-Role'))->with('profile', 'mission'))
            ->allowedFilters(
                AllowedFilter::custom('search', new FiltersParticipationSearch),
                AllowedFilter::exact('mission.id'),
                AllowedFilter::exact('mission.name'),
                AllowedFilter::exact('mission.department'),
                AllowedFilter::exact('mission.structure.name'),
                AllowedFilter::exact('mission.structure.id'),
                AllowedFilter::exact('mission.template.id'),
                AllowedFilter::exact('profile.id'),
                AllowedFilter::scope('ofReseau'),
                AllowedFilter::scope('OfTerritoire'),
                'state',
                'mission.zip',
                'mission.type',
            )
            ->allowedIncludes([
                'conversation.latestMessage',
                'profile.avatar',
            ])
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Request $request, Participation $participation)
    {
        $participation = $participation->load(['mission', 'profile', 'conversation', 'conversation.latestMessage', 'mission.responsable', 'profile.skills', 'profile.domaines', 'profile.avatar']);
        // $participation->mission->append(['full_address']);

        return $participation;
    }

    public function export(Request $request)
    {
        return Excel::download(new ParticipationsExport($request), 'profiles.xlsx');
    }

    public function store(ParticipationCreateRequest $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $participationCount = Participation::where('state', '!=', 'Annulée')->where('profile_id', request("profile_id"))
            ->where('mission_id', request("mission_id"))->count();

        if ($participationCount > 0) {
            abort(422, "Désolé, vous avez déjà participé à cette mission !");
        }

        $mission = Mission::find(request("mission_id"));

        if ($mission && $mission->has_places_left) {
            $participation = Participation::create($request->validated());
            if (request('content')) {
                // En attendant de régler le souci des responsables sans user
                $user = $mission->responsable->user ?? $mission->structure->user;
                $conversation = $currentUser->startConversation($user, $participation);
                $currentUser->sendMessage($conversation->id, request('content'));
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

        if ($participation->conversation) {
            // Trigger updated_at refresh.
            $participation->conversation->touch();
        }

        return $participation->load(['conversation', 'conversation.latestMessage']);
    }

    public function decline(ParticipationDeclineRequest $request, Participation $participation)
    {
        if ($participation->conversation) {
            $currentUser = User::find(Auth::guard('api')->user()->id);

            $participation->conversation->messages()->create([
                'from_id' => $currentUser->id,
                'type' => 'contextual',
                'content' => 'La participation a été déclinée',
                'contextual_state' => 'Refusée',
                'contextual_reason' => $request->input('reason')
            ]);

            if ($request->input('content')) {
                $currentUser->sendMessage($participation->conversation->id, $request->input('content'));
            }

            $currentUser->markConversationAsRead($participation->conversation);

            // Trigger updated_at refresh.
            $participation->conversation->touch();

            if ($request->input('reason') == 'mission_terminated') {
                $participation->mission->state = 'Terminée';
                $participation->mission->save();
            }

            $participation->profile->notify(new ParticipationDeclined($participation, $request->input('reason')));
        }

        $participation->update(['state' => 'Refusée']);

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation->load(['conversation', 'conversation.latestMessage']);
    }

    public function cancel(ParticipationCancelRequest $request, Participation $participation)
    {
        $participation->load('conversation');

        if ($participation->conversation) {
            $currentUser = User::find(Auth::guard('api')->user()->id);

            $participation->conversation->messages()->create([
                'from_id' => $currentUser->id,
                'type' => 'contextual',
                'content' => 'La participation a été annulée par ' . $currentUser->profile->full_name,
                'contextual_state' => 'Annulée par bénévole',
                'contextual_reason' => $request->input('reason')
            ]);

            if ($request->input('content')) {
                $currentUser->sendMessage($participation->conversation->id, $request->input('content'));
            }

            $currentUser->markConversationAsRead($participation->conversation);

            // Trigger updated_at refresh.
            $participation->conversation->touch();

            $participation->mission->responsable->notify(new ParticipationBenevoleCanceled($participation, $request->input('reason')));
        }

        $participation->state = 'Annulée';
        $participation->saveQuietly();

        // Places left & Algolia
        if ($participation->mission) {
            $participation->mission->update();
        }

        return $participation;
    }

    // public function cancel(Request $request, Participation $participation)
    // {
    //     if (Auth::guard('api')->user()->profile->id != $participation->profile_id) {
    //         abort(403, 'Cette participation ne vous appartient pas');
    //     }

    //     $participation->update(['state' => 'Annulée']);

    //     return $participation;
    // }

    public function delete(ParticipationDeleteRequest $request, Participation $participation)
    {
        return (string) $participation->delete();
    }

    // public function massValidation(Request $request)
    // {
    //     $participations = Participation::role('responsable')->where('state', 'En attente de validation')->get();
    //     foreach ($participations as $participation) {
    //         $participation->update(['state' => 'Validée']);
    //     }
    // }

    public function conversation(ParticipationManageRequest $request, Participation $participation)
    {
        if ($participation->conversation) {
            $conversation = Conversation::with('latestMessage')->find($participation->conversation->id);
            return $conversation;
        }

        return null;
    }

    public function benevole(ParticipationManageRequest $request, Participation $participation)
    {
        return $participation->profile->append('roles', 'has_user', 'domaines');
    }

    public function mission(Request $request, Participation $participation)
    {
        return $participation->mission->load('structure');
    }

    public function benevoleName(Request $request, Participation $participation)
    {
        return $participation->profile->only(['id', 'first_name', 'last_name']);
    }

    public function temoignage(Request $request, Participation $participation)
    {
        return Temoignage::where('participation_id', $participation->id)->first();
    }
}
