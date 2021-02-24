<?php

namespace App\Observers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageObserver
{
    public function created(Message $message)
    {
        $user = Auth::guard('api')->user();

        // Quand un nouveau message dans la conversation
        $participation = $message->conversation->conversable;
        // On vérifie que ce n'est pas le créateur de la conversation
        if ($participation->profile_id != $user->profile->id) {
            if ($message->conversation) {
                $message->conversation->setResponseTime()->save();
            }
        }
    }
}
