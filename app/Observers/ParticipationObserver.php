<?php

namespace App\Observers;

use App\Models\Participation;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationDeclined;
use App\Notifications\ParticipationFinished;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        if ($participation->state == 'En attente de validation') {
            if ($participation->mission->tuteur) {
                $participation->mission->tuteur->notify(new ParticipationWaitingValidation($participation));
            }
        }
    }

    public function updated(Participation $participation)
    {
        $oldState = $participation->getOriginal('state');
        $newState = $participation->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'En attente de validation':
                    if ($participation->mission->tuteur) {
                        $participation->mission->tuteur->notify(new ParticipationWaitingValidation($participation));
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
                case 'Effectuée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationFinished($participation));
                    }
                    break;
                case 'Refusée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationDeclined($participation));
                    }
                    break;
            }
        }
    }

    public function deleted(Participation $participation)
    {
        $participation->mission->update();
    }
}
