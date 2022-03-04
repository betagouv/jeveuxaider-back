<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\Participation;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use App\Filters\FiltersConversationSearch;
use App\Http\Requests\ConversationRequest;
use App\Filters\FiltersConversationExclude;
use App\Filters\FiltersConversationStatus;

class ConversationsController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(
            Conversation::role($request->header('Context-Role'))->with(
                ['latestMessage', 'users', 'conversable' => function (MorphTo $morphTo) {
                    $morphTo->morphWith(
                        [
                            Participation::class => [
                                'mission.structure:id,name',
                                // 'mission.domaine',
                                // 'mission.responsable',
                                // 'profile'
                            ],
                        ]
                    );
                }]
            )
        )
            ->allowedFilters(
                [
                    AllowedFilter::custom('search', new FiltersConversationSearch),
                    AllowedFilter::custom('exclude', new FiltersConversationExclude),
                    AllowedFilter::custom('status', new FiltersConversationStatus),
                ]
            )
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function show(ConversationRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $currentUser->markConversationAsRead($conversation);

        return Conversation::with(
            ['users', 'latestMessage', 'conversable' => function (MorphTo $morphTo) {
                $morphTo->morphWith(
                    [
                        Participation::class => [
                            'mission.structure:id,name',
                            // 'mission.domaine',
                            'mission.responsable',
                            'profile'
                        ],
                    ]
                );
            }]
        )->where('id', $conversation->id)->first();
    }

    public function messages(ConversationRequest $request, Conversation $conversation)
    {

        return QueryBuilder::for(Message::where('conversation_id', $conversation->id)->with(['from']))
            ->defaultSort('-id')
            ->paginate($request->input('pagination') ?? config('query-builder.results_per_page'));
    }

    public function benevole(ConversationRequest $request, Conversation $conversation)
    {
        return Profile::with(['structures:id,name','domaines'])->find($conversation->conversable->profile_id);
    }

    public function setStatus(ConversationRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $currentUser->setConversationStatus($conversation, request('status'));
        return;
    }
}
