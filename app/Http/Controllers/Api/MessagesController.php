<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Http\Requests\ContextualMessageRequest;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessagesController extends Controller
{
    public function store(MessageRequest $request, Conversation $conversation)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);
        $message = $currentUser->sendMessage($conversation->id, request('content'));
        $message->from; // HACK

        $currentUser->markConversationAsRead($conversation);

        // Trigger updated_at refresh.
        $conversation->touch();

        return $message;
    }

    // public function storeContextualMessage(ContextualMessageRequest $request, Conversation $conversation)
    // {
    //     $currentUser = User::find(Auth::guard('api')->user()->id);

    //     $message = Message::create([
    //         'from_id' => $currentUser->id,
    //         'content' => $request->input('content'),
    //         'type' => 'contextual',
    //         'contextual_state' => $request->input('contextual_state'),
    //         'contextual_reason' => $request->input('contextual_reason'),
    //     ]);

    //     $currentUser->markConversationAsRead($conversation);

    //     // Trigger updated_at refresh.
    //     $conversation->touch();

    //     return $message;
    // }
}
