<?php

namespace App\Observers;

use App\Jobs\NotifyVolunteerOfUnreadMessage;
use App\Models\Message;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\Structure;
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

        if (!$conversable) {
            return;
        }

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
                if ($participation->state == 'En attente de validation' && $message->type === 'chat') {

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
                $lastMessage = $message->conversation->messages()->where('type', 'chat')->orderByDesc('created_at')->skip(1)->first(); // 0 est le nouveau message
                if ($lastMessage->from_id == $message->from_id) {
                    // 1 heure entre deux emails de la même personne
                    $diffInMinutes = $message->created_at->diffInMinutes($lastMessage->created_at);
                    if ($diffInMinutes < 60) {
                        $send = false;
                    }
                }
            }
        }

        // GET USERS TO NOTIFY
        $toUsers = $message->conversation->users->filter(function ($user) use ($message) {
            return $user->id !== $message->from_id;
        });

        if ($toUsers->count() === 0) {
            $send = false;
        }

        // PARTICIPATION - Si le message est à destination du responsable et qu'il n'est pas en realtime
        if ($send && $conversable::class == Participation::class) {
            $toUsers->each(function ($toUser) use ($message, $conversable) {
                $recipient = $conversable->profile->user->id === $toUser->id ? 'benevole' : 'responsable';
                if ($recipient === 'responsable') {
                    if ($toUser->profile->notification__responsable_frequency === 'realtime') {
                        $toUser->notify(new MessageParticipationCreated($message));
                    }
                } else {
                    $toUser->notify(new MessageParticipationCreated($message));
                }
            });
        }

        // STRUCTURE - Si le message est à destination du referent ou du responsable et qu'il n'est pas en realtime
        if ($send && $conversable::class == Structure::class) {
            $toUsers->each(function ($toUser) use ($message, $conversable) {
                if ($toUser->context_role == 'referent' && $toUser->profile->notification__referent_frequency === 'realtime') {
                    $toUser->notify(new MessageStructureCreated($message));
                }
                if ($toUser->context_role == 'responsable' && $toUser->profile->notification__responsable_frequency === 'realtime') {
                    $toUser->notify(new MessageStructureCreated($message));
                }
                if ($toUser->context_role == 'admin') {
                    $toUser->notify(new MessageStructureCreated($message));
                }
            });
        }

        // MISSION - Si le message est à destination du referent ou du responsable et qu'il n'est pas en realtime
        if ($send && $conversable::class == Mission::class) {
            $toUsers->each(function ($toUser) use ($message, $conversable) {
                if ($toUser->context_role == 'referent' && $toUser->profile->notification__referent_frequency === 'realtime') {
                    $toUser->notify(new MessageMissionCreated($message));
                }
                if ($toUser->context_role == 'responsable' && $toUser->profile->notification__responsable_frequency === 'realtime') {
                    $toUser->notify(new MessageMissionCreated($message));
                }
                if ($toUser->context_role == 'admin') {
                    $toUser->notify(new MessageMissionCreated($message));
                }
            });
        }

        // Notification SMS si première réponse d'un reponsable
        if ($conversable::class == Participation::class && $message->type === 'chat') {
            $conversable->loadMissing(['profile', 'profile.user']);
            if ($user->id !== $conversable->profile->user->id) {
                // @todo: Set delay to now()->addDays(3)->setTime(9, 30)
                NotifyVolunteerOfUnreadMessage::dispatch($message)->delay(now()->addSeconds(10));
            }
        }
    }
}
