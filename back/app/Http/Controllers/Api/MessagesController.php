<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Models\Conversation;
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
}
