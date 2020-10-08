<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ConversationsController extends Controller
{
    public function index(Request $request)
    {
        // TODO : Get Conversations of current user ( scope ? )
        // TODO : With last message only
        // TODO : Get last message dans le model
        return QueryBuilder::for(Conversation::with(['messages' => function ($query) {
            $query->latest()->first();
        }, 'users', 'participation.mission.domaine', 'participation.mission.structure:id,name']))
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function messages(Request $request, Conversation $conversation)
    {
        // TODO : Fill readAt for current user
        // TODO : Renvoyer les 10 derniers par odre croissant
        return QueryBuilder::for(Message::where('conversation_id', $conversation->id)->with(['from']))
            ->defaultSort('-updated_at')
            ->paginate(config('query-builder.results_per_page'));
    }
}
