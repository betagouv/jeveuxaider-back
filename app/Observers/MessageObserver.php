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

        if (!$message->conversation->response_time && $message->conversation->conversable->profile_id != $user->profile->id) {
            $message->conversation->response_time = $message->created_at->timestamp - $message->conversation->conversable->created_at->timestamp;
            $message->conversation->save();
        }
    }
}
