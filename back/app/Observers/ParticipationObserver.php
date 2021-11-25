<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Participation;
use App\Models\Structure;
use App\Notifications\MissionAlmostFull;
use App\Notifications\ParticipationBeingProcessed;
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

        // Update Places left & Algolia
        $participation->mission->update();

        if (
            $participation->mission->participations_max > 10 &&
            $participation->mission->places_left === 1
        ) {
            $participation->mission->responsable->notify(new MissionAlmostFull($participation->mission));
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
                case 'En cours de traitement':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationBeingProcessed($participation));
                    }
                    break;
                case 'Validée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationValidated($participation));

                        // MAJ SENDINBLUE
                        if (config('app.env') === 'production') {
                            SendinblueSyncUser::dispatch($participation->profile->user);
                        }
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
    }

    public function deleted(Participation $participation)
    {
        if ($participation->mission) {
            $participation->mission->update();
        }

        // MAJ SENDINBLUE
        if (config('app.env') === 'production') {
            if ($participation->profile) {
                SendinblueSyncUser::dispatch($participation->profile->user);
            }
        }
    }
}
