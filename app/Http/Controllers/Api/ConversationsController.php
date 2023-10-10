<?php

namespace App\Http\Controllers\API;

use App\Filters\FiltersConversationMissionName;
use App\Filters\FiltersConversationMissionZipCity;
use App\Filters\FiltersConversationStructureName;
use App\Filters\FiltersConversationParticipationState;
use App\Filters\FiltersConversationSearch;
use App\Filters\FiltersConversationType;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConversationRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participation;
use App\Models\Mission;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use App\Http\Requests\MessageRequest;
use Illuminate\Database\Eloquent\Builder;

class ConversationsController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(
            Conversation::role($request->header('Context-Role'))->whereHas('conversable')->with(
                ['users', 'users.profile.avatar', 'conversable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith(
                        [
                            Participation::class => [
                                'mission.structure:id,name',
                            ],
                        ]
                    );
                }]
            )
        )
            ->allowedFilters(
                [
                    AllowedFilter::custom('search', new FiltersConversationSearch),
                    AllowedFilter::custom('type', new FiltersConversationType),
                    AllowedFilter::custom('participation_state', new FiltersConversationParticipationState),
                    AllowedFilter::custom('mission_name', new FiltersConversationMissionName),
                    AllowedFilter::custom('mission_zip_city', new FiltersConversationMissionZipCity),
                    AllowedFilter::custom('structure_name', new FiltersConversationStructureName),
                    AllowedFilter::exact('conversable_type'),
                    AllowedFilter::exact('conversable_id'),
                    AllowedFilter::scope('with_users')
                ]
            )
            ->defaultSort('-updated_at')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'))
        ;
    }

    public function show(ConversationRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        if (!$request->headers->has('Impersonating')) {
            $currentUser->markConversationAsRead($conversation);
        }

        return Conversation::with(
            ['users', 'users.profile.avatar', 'latestMessage', 'users.structures', 'users.territoires', 'users.roles', 'conversable' => function (MorphTo $morphTo) {
                $morphTo->morphWith(
                    [
                        Participation::class => [
                            'mission.structure:id,name',
                            'mission.responsable',
                            'profile',
                            'temoignage'
                        ],
                        Mission::class => [
                            'structure:id,name',
                            'responsable',
                        ],
                    ]
                );
            }]
        )->where('id', $conversation->id)->first();
    }

    public function store(Request $request)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $toUser = User::find(request('toUser'));
        $className = request('conversableType');
        $conversable = $className::find(request('conversableId'));

        $conversation = $currentUser->startConversation($toUser, $conversable);
        $currentUser->sendMessage($conversation->id, request('message'));

        return $conversation;
    }

    public function messages(ConversationRequest $request, Conversation $conversation)
    {
        return QueryBuilder::for(Message::where('conversation_id', $conversation->id)->with(['from', 'from.profile.avatar']))
            ->allowedFilters(
                [
                    AllowedFilter::callback('after_message_id', function (Builder $query, $value) {
                        $query->where('messages.id', '>', $value);
                    }),
                    AllowedFilter::callback('before_message_id', function (Builder $query, $value) {
                        $query->where('messages.id', '<', $value);
                    }),
                ]
            )
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'))
        ;
    }

    public function storeMessage(MessageRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $message = $currentUser->sendMessage($conversation->id, request('content'));

        $currentUser->markConversationAsRead($conversation);
        $conversation->touch();

        return $message;
    }

    public function archive(ConversationRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $currentUser->setConversationStatus($conversation, false);
    }

    public function unarchive(ConversationRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $currentUser->setConversationStatus($conversation, true);
    }
}