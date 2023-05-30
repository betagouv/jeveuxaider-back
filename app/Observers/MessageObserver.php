<?php

namespace App\Observers;

use App\Jobs\StructureCalculateScore;
use App\Models\Message;
use App\Models\Participation;
use App\Models\Structure;
use App\Notifications\MessageCreated;
use App\Notifications\MessageMissionCreated;
use App\Notifications\MessageParticipationCreated;
use App\Notifications\MessageStructureCreated;
use Illuminate\Support\Facades\Auth;

class MessageObserver
{
    public function created(Message $message)
    {
        $user = Auth::guard('api')->user();

        // Quand un nouveau message dans la conversation
        $conversable = $message->conversation->conversable;
        if ($conversable::class == Participation::class) {
            $participation = $conversable;
            // On vérifie que ce n'est pas le bénévole (= le créateur de la conversation)
            if ($user && $participation->profile_id != $user->profile->id) {
                if ($message->conversation) {
                    $message->conversation->setResponseTime();
                    $message->conversation->timestamps = false;
                    $message->conversation->save();
                }
            }

            // Si la participation est en attente de validation, et que le responsable envoie un message
            // la participation devient "En cours de traitement"
            if ($user && $participation->profile_id != $user->profile->id) {
                if ($participation->state == 'En attente de validation') {

                    // Log (because saveQuietly)
                    activity()
                        ->causedBy($user)
                        ->performedOn($participation)
                        ->withProperties([
                                'attributes' => ['state' => 'En cours de traitement'],
                                'old' => ['state' => $participation->state]
                            ])
                        ->event('updated')
                        ->log('updated');

                    $participation->state = 'En cours de traitement';
                    $participation->saveQuietly(); // Quietly pour éviter la double notif : message + en cours de traitement
                    // RESPONSE RATIO
                    // @todo: Only leave StructureCalculateScore
                    $structure = $participation->mission->structure;
                    $structure->setResponseRatio();
                    $structure->saveQuietly();
                    StructureCalculateScore::dispatch($structure);
                }
            }
        }

        // Envoyer un message au destinataire
        $send = true;
        if ($message->type != 'chat') {
            $send = false;
        }

        // Si c'est le premier message il y a déjà une notif email liée à la participation
        if ($conversable::class == Participation::class && $message->conversation->messages->count() == 1) {
            $send = false;
        }

        // Éviter le flood
        if ($send) {
            if ($message->conversation->messages->where('type', 'chat')->count() > 1) {
                $lastMessage = $message->conversation->messages->where('type', 'chat')->sortBy([['created_at', 'desc']])[1]; // 0 est le nouveau message
                if ($lastMessage->from_id == $message->from_id) {
                    // 1 heure entre deux emails de la même personne
                    $diffInMinutes = $message->created_at->diffInMinutes($lastMessage->created_at);
                    if ($diffInMinutes < 60) {
                        $send = false;
                    }
                }
            }
        }

        $toUser = $message->conversation->users->filter(function ($user) use ($message) {
            return $user->id !== $message->from_id;
        })->first();

        // PARTICIPATION - Si le message est à destination du responsable et qu'il n'est pas en realtime
        if ($send && $toUser) {
            if ($conversable::class == Participation::class && $conversable->mission->responsable->user_id == $toUser->id) {
                if ($toUser->profile->notification__responsable_frequency !== 'realtime') {
                    $send = false;
                }
            }
        }

        // STRUCTURE - Si le message est à destination du referent ou du responsable et qu'il n'est pas en realtime
        if ($send && $toUser) {
            if ($conversable::class == Structure::class) {
                if ($toUser->context_role == 'referent' && $toUser->profile->notification__referent_frequency !== 'realtime') {
                    $send = false;
                }
                if ($toUser->context_role == 'responsable' && $toUser->profile->notification__responsable_frequency !== 'realtime') {
                    $send = false;
                }
            }
        }

        if ($send && $toUser) {
            if ($conversable::class == Participation::class) {
                $toUser->notify(new MessageParticipationCreated($message));
            }
            if ($conversable::class == Structure::class) {
                $toUser->notify(new MessageStructureCreated($message));
            }
            if ($conversable::class == Mission::class) {
                $toUser->notify(new MessageMissionCreated($message));
            }
        }
    }
}
