<?php

namespace App\Observers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageObserver
{
    public function created(Message $message)
    {
        $user = Auth::guard('api')->user();

        // SET CONVERSATION RESPONSE TIME IF NULL
        $participation = $message->conversation->conversable;
        if (!$message->conversation->response_time && $participation->profile_id != $user->profile->id) {
            $message->conversation->response_time = $message->created_at->timestamp - $participation->created_at->timestamp;
            $message->conversation->save();
        }
    }
}
