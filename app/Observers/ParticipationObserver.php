<?php

namespace App\Observers;

use App\Models\Participation;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;

// use App\Notifications\ParticipationCanceled;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        if ($participation->mission) {
            if ($participation->mission->shouldBeSearchable()) {
                $participation->mission->searchable();
            } else {
                $participation->mission->unsearchable();
            }
        }

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
                case 'Mission validée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationValidated($participation));
                    }
                    break;
                // case 'Mission annulée':
                //     if ($participation->profile) {
                //         $participation->profile->notify(new ParticipationCanceled($participation));
                //     }
                //     break;
                
            }
        }
    }
}
