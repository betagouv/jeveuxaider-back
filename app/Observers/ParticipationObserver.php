<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\MissionAlmostFull;
use App\Notifications\MissionFull;
use App\Notifications\ParticipationBeingProcessed;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ParticipationValidatedCejAdviser;
use App\Notifications\ParticipationCreatedFTAdviser;
use Illuminate\Support\Facades\Notification;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        if ($participation->state == 'En attente de validation') {
            if ($participation->mission->has('responsables')) {
                $participation->mission->responsables->each(function ($responsable) use ($participation) {
                    if($responsable->notification__responsable_frequency == 'realtime') {
                        $responsable->notify(new ParticipationWaitingValidation($participation));
                    }
                });
            }
            if (!empty($participation->profile->cej_email_adviser)) {
                Notification::route('mail', $participation->profile->cej_email_adviser)->notify(new ParticipationValidatedCejAdviser($participation));
            }
            if (!empty($participation->profile->ft_email_adviser)) {
                Notification::route('mail', $participation->profile->ft_email_adviser)->notify(new ParticipationCreatedFTAdviser($participation));
            }
        }

        // Score - Take new participation into account
        $participation->mission->structure->calculateScore();

        // Maj Sendinblue
        if (config('services.sendinblue.sync')) {
            SendinblueSyncUser::dispatch($participation->profile->user);
        }

        // Update Places left & Algolia
        $participation->mission->update();

        if (
            $participation->mission->participations_max > 5 &&
            $participation->mission->places_left === 1
        ) {
            if ($participation->mission->has('responsables')) {
                $participation->mission->responsables->each(function ($responsable) use ($participation) {
                    $responsable->notify(new MissionAlmostFull($participation->mission));
                });
            }
        } elseif ($participation->mission->places_left <= 0) {
            if ($participation->mission->has('responsables')) {
                $participation->mission->responsables->each(function ($responsable) use ($participation) {
                    $responsable->notify(new MissionFull($participation->mission));
                });
            }
        }
    }

    public function updated(Participation $participation)
    {
        $oldState = $participation->getOriginal('state');
        $newState = $participation->state;
        $currentUser = User::find(Auth::guard('api')->user()?->id);

        $participation->loadMissing(['profile']);

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En cours de traitement':
                    if ($participation->profile && $currentUser && !$currentUser->isAdmin()) {
                        $participation->profile->notify(new ParticipationBeingProcessed($participation));
                    }
                    break;
                case 'Validée':
                    if ($participation->profile) {
                        if ($currentUser && !$currentUser->isAdmin()) {
                            $participation->profile->notify(new ParticipationValidated($participation));
                        }

                        // MAJ SENDINBLUE
                        if (config('services.sendinblue.sync')) {
                            SendinblueSyncUser::dispatch($participation->profile->user);
                        }
                    }
                    break;
                case 'Annulée':
                    if ($participation->profile && $currentUser && !$currentUser->isAdmin()) {
                        $participation->profile->notify(new ParticipationCanceled($participation));
                    }
                    break;
            }
        }

        // Update structure's score
        if ($oldState != $newState) {
            $participation->loadMissing(['conversation', 'mission.structure']);
            if (in_array($oldState, ['En attente de validation', 'En cours de traitement'])) {
                $participation->mission->structure->calculateScore();
            }
            if ($participation->conversation) {
                if ($newState != 'Refusée') {
                    $participation->conversation->messages()->create([
                        'content' => 'La participation a été ' . mb_strtolower($newState),
                        'type' => 'contextual',
                        'contextual_state' => $newState,
                    ]);
                    $currentUser?->markConversationAsRead($participation->conversation);
                }
                $participation->conversation->setResponseTime();
                $participation->conversation->timestamps = false;
                $participation->conversation->save();
            }
        }
    }

    public function deleted(Participation $participation)
    {
        if ($participation->mission) {
            $participation->mission->update();
        }

        // MAJ SENDINBLUE
        if (config('services.sendinblue.sync')) {
            if ($participation->profile) {
                SendinblueSyncUser::dispatch($participation->profile->user);
            }
        }
    }
}
