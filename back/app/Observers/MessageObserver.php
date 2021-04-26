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

        // Envoyer un message au destinaire
        $send = true;
        if($message->type != 'chat') {
            $send = false;
        }
        // Si c'est le premier message il y a déjà une notif liée à la participation
        if($message->conversation->messages->count() == 1) {
            $send = false;
        }
        // Éviter le flood
        if($send) {
            $lastMessage = $message->conversation->messages->sortBy([['created_at', 'desc']])[1]; // 0 est le nouveau message
            if($lastMessage->from_id == $message->from_id) {
                // 1 heure entre deux emails de la même personne
                $diffInMinutes = $message->created_at->diffInMinutes($lastMessage->created_at);
                if($diffInMinutes < 60) {
                    $send = false;
                }
            }
        }

        if($send) {
            $toUser = $message->conversation->users->filter(function($user) use ($message) {
                return $user->id !== $message->from_id;
            })->first();
            $toUser->notify(new MessageCreated($message));
        }
    }
}
