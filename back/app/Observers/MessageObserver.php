<?php

namespace App\Observers;

use App\Models\Message;
use App\Notifications\MessageCreated;
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

        // TODO : Envoyer le message via queue, et éviter le spam

        // Envoyer un message au destinaire
        // Si ce n'est pas un message contextuel et ce n'est pas le premier message
        // if($message->type == 'chat' && $message->conversation->messages->count() > 1) { 
        //     $toUser = $message->conversation->users->filter(function($user) use ($message) {
        //         return $user->id !== $message->from_id;
        //     })->first();
        //     $toUser->notify(new MessageCreated($message));
        // }
    }
}
