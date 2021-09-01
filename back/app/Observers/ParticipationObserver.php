<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Participation;
use App\Models\Structure;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ParticipationCanceled;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        if ($participation->state == 'En attente de validation') {
            if ($participation->mission->responsable) {
                $participation->mission->responsable->notify(new ParticipationWaitingValidation($participation));
            }
        }

        // RESPONSE RATIO
        $structure = $participation->mission->structure;
        $structure->setResponseRatio();
        $structure->saveQuietly();

        // Maj Sendinblue
        if (config('app.env') === 'production') {
            SendinblueSyncUser::dispatch($participation->profile->user);
        }
    }

    public function updated(Participation $participation)
    {
        $oldState = $participation->getOriginal('state');
        $newState = $participation->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En attente de validation':
                    if ($participation->mission->responsable) {
                        $participation->mission->responsable->notify(new ParticipationWaitingValidation($participation));
                    }
                    break;
                case 'Validée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationValidated($participation));
                    }
                    break;
                case 'Annulée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationCanceled($participation));
                    }
                    break;
            }
        }

        // SET STRUCTURE RESPONSE RATIO
        if ($oldState != $newState) {
            if ($oldState == 'En attente de validation') {
                $participation->mission->structure->setResponseRatio()->saveQuietly();
            }
            if ($participation->conversation) {
                if ($newState != 'Refusée') {
                    $participation->conversation->messages()->create([
                        'content' => 'La participation a été ' . mb_strtolower($newState),
                        'type' => 'contextual',
                        'contextual_state' => $newState,
                    ]);
                }
                $participation->conversation->setResponseTime()->save();
            }
        }

        // Maj Sendinblue : Le nombre de participations validées peut avoir changé
        if (config('app.env') === 'production') {
            if ($oldState != $newState) {
                SendinblueSyncUser::dispatch($participation->profile->user);
            }
        }
    }

    public function deleted(Participation $participation)
    {
        if ($participation->mission) {
            $participation->mission->update();
        }

        // Maj Sendinblue
        if (config('app.env') === 'production') {
            SendinblueSyncUser::dispatch($participation->profile->user);
        }
    }
}
