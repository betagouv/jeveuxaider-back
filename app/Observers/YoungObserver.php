<?php

namespace App\Observers;

use App\Models\Young;
use App\Notifications\YoungMissionAssigned;
use App\Notifications\YoungMissionInProgress;

class YoungObserver
{
    /**
     * Listen to the Young created event.
     *
     * @param  \App\Models\Young  $young
     * @return void
     */
    public function created(Young $young)
    {
        // If no mission nor tuteur, do nothing
        if (!$young->mission || !$young->mission->tuteur) {
            return;
        }

        switch ($young->state) {
            case 'Mission proposée':
                $young->mission->tuteur->notify(new YoungMissionAssigned($young));
                break;
            case 'Mission en cours':
                $young->mission->tuteur->notify(new YoungMissionInProgress($young));
                break;
        }
    }

    /**
     * Listen to the Young updated event.
     *
     * @param  \App\Models\Young  $young
     * @return void
     */
    public function updated(Young $young)
    {

        // If no mission nor tuteur, do nothing
        if (!$young->mission || !$young->mission->tuteur) {
            return;
        }

        // Mission en cours
        if ($young->state != $young->getOriginal('state')) {
            switch ($young->state) {
                case 'Mission proposée':
                    $young->mission->tuteur->notify(new YoungMissionAssigned($young));
                    break;
                case 'Mission en cours':
                    $young->mission->tuteur->notify(new YoungMissionInProgress($young));
                    break;
            }
        }
    }

    /**
     * Listen to the Young deleting event.
     *
     * @param  \App\Models\Young  $young
     * @return void
     */
    public function deleting(Young $young)
    {
        //
    }
}
