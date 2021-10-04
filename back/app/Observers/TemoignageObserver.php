<?php

namespace App\Observers;

use App\Models\NotificationTemoignage;
use App\Models\Temoignage;

class TemoignageObserver
{
    /**
     * Handle the Temoignage "created" event.
     *
     * @param  \App\Models\Temoignage  $temoignage
     * @return void
     */
    public function created(Temoignage $temoignage)
    {
        $temoignage->participation->notificationTemoignage->delete();
    }

    /**
     * Handle the Temoignage "updated" event.
     *
     * @param  \App\Models\Temoignage  $temoignage
     * @return void
     */
    public function updated(Temoignage $temoignage)
    {
        //
    }

    /**
     * Handle the Temoignage "deleted" event.
     *
     * @param  \App\Models\Temoignage  $temoignage
     * @return void
     */
    public function deleted(Temoignage $temoignage)
    {
        //
    }

    /**
     * Handle the Temoignage "restored" event.
     *
     * @param  \App\Models\Temoignage  $temoignage
     * @return void
     */
    public function restored(Temoignage $temoignage)
    {
        //
    }

    /**
     * Handle the Temoignage "force deleted" event.
     *
     * @param  \App\Models\Temoignage  $temoignage
     * @return void
     */
    public function forceDeleted(Temoignage $temoignage)
    {
        //
    }
}
