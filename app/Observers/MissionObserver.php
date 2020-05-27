<?php

namespace App\Observers;

use App\Models\Mission;
use App\Models\Participation;
use App\Models\Profile;
use App\Notifications\MissionValidated;
use App\Notifications\MissionWaitingCorrection;
use App\Notifications\MissionWaitingValidation;
use App\Notifications\MissionSignaled;
use App\Notifications\MissionFinished;
use App\Notifications\MissionSubmitted;

class MissionObserver
{
    /**
     * Listen to the Mission created event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function created(Mission $mission)
    {
        if ($mission->state == 'En attente de validation') {
            if ($mission->tuteur) {
                $mission->tuteur->notify(new MissionWaitingValidation($mission));
            }
            if ($mission->department) {
                Profile::where('referent_department', $mission->department)->get()->map(function ($profile) use ($mission) {
                    $profile->notify(new MissionSubmitted($mission));
                });
            }
        }

        if ($mission->state == 'Validée') {
            if ($mission->tuteur) {
                $mission->tuteur->notify(new MissionValidated($mission));
            }
        }
    }

    /**
     * Listen to the Mission updated event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function updated(Mission $mission)
    {
        $oldState = $mission->getOriginal('state');
        $newState = $mission->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'Validée':
                    if ($mission->tuteur) {
                        $mission->tuteur->notify(new MissionValidated($mission));
                    }
                    break;
                case 'En attente de validation':
                    if ($mission->tuteur) {
                        $mission->tuteur->notify(new MissionWaitingValidation($mission));
                    }
                    if ($mission->department) {
                        Profile::where('referent_department', $mission->department)->get()->map(function ($profile) use ($mission) {
                            $profile->notify(new MissionSubmitted($mission));
                        });
                    }
                    break;
                case 'En attente de correction':
                    if ($mission->tuteur) {
                        $mission->tuteur->notify(new MissionWaitingCorrection($mission));
                    }
                    break;
                case 'Signalée':
                    if ($mission->tuteur) {
                        $mission->tuteur->notify(new MissionSignaled($mission));
                        if ($mission->participations) {
                            foreach ($mission->participations as $participation) {
                                $participation->update(['state' => 'Mission signalée']);
                            }
                        }
                    }
                    break;
                case 'Annulée':
                    if ($mission->tuteur) {
                        foreach ($mission->participations->where("state", "En attente de validation") as $participation) {
                            $participation->update(['state' => 'Mission annulée']);
                        }
                    }
                    break;
                case 'Terminée':
                    if ($mission->tuteur) {
                        $mission->tuteur->notify(new MissionFinished($mission));
                        foreach ($mission->participations->whereIn("state", ["Mission validée", "Mission en cours"]) as $participation) {
                            $participation->update(['state' => 'Mission effectuée']);
                        }
                        foreach ($mission->participations->where("state", "En attente de validation") as $participation) {
                            $participation->update(['state' => 'Participation déclinée']);
                        }
                    }
                    break;
            }
        }
    }

    public function saving(Mission $mission)
    {
        // Calcul Places Left
        $mission->places_left = $mission->participations_max - $mission->participations->whereIn('state', Participation::ACTIVE_STATUS)->count();
    }

    /**
     * Listen to the Mission deleting event.
     *
     * @param  \App\Models\Mission  $mission
     * @return void
     */
    public function deleting(Mission $mission)
    {
        //
    }
}
