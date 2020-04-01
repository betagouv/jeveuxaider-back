<?php

namespace App\Observers;

use App\Models\Participation;
use App\Notifications\ParticipationValidated;
use App\Notifications\ParticipationWaitingValidation;
use App\Notifications\ParticipationCanceled;
use App\Notifications\ParticipationSignaled;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        $this->updateMission($participation);

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

        $this->updateMission($participation);

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
                case 'Mission annulée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationCanceled($participation));
                    }
                    break;
                case 'Mission signalée':
                    if ($participation->profile) {
                        $participation->profile->notify(new ParticipationSignaled($participation));
                    }
                    break;
            }
        }
    }

    public function deleted(Participation $participation)
    {
        // $oldState = $participation->getOriginal('state');
        // $newState = $participation->state;

        $this->updateMission($participation);
    }

    private function updateMission($participation) {
        // Permet de calculer le nombre de places restantes + Envoie à Algolia
        $participation->mission->update();
        /*
        if ($participation->mission) {
            if ($participation->mission->shouldBeSearchable()) {
                $participation->mission->searchable();
            } else {
                $participation->mission->unsearchable();
            }
        }*/
    }
}
