<?php

namespace App\Observers;

use App\Models\NotificationTemoignage;
use App\Notifications\NotificationTemoignageCreate;

class NotificationTemoignageObserver
{
    /**
     * Handle the NotificationTemoignage "created" event.
     *
     * @param  \App\Models\NotificationTemoignage  $notificationTemoignage
     * @return void
     */
    public function created(NotificationTemoignage $notificationTemoignage)
    {
        $notificationTemoignage->participation->profile->user
            ->notify(new NotificationTemoignageCreate($notificationTemoignage));
    }

    /**
     * Handle the NotificationTemoignage "updated" event.
     *
     * @param  \App\Models\NotificationTemoignage  $notificationTemoignage
     * @return void
     */
    public function updated(NotificationTemoignage $notificationTemoignage)
    {
        //
    }

    /**
     * Handle the NotificationTemoignage "deleted" event.
     *
     * @param  \App\Models\NotificationTemoignage  $notificationTemoignage
     * @return void
     */
    public function deleted(NotificationTemoignage $notificationTemoignage)
    {
        //
    }

    /**
     * Handle the NotificationTemoignage "restored" event.
     *
     * @param  \App\Models\NotificationTemoignage  $notificationTemoignage
     * @return void
     */
    public function restored(NotificationTemoignage $notificationTemoignage)
    {
        //
    }

    /**
     * Handle the NotificationTemoignage "force deleted" event.
     *
     * @param  \App\Models\NotificationTemoignage  $notificationTemoignage
     * @return void
     */
    public function forceDeleted(NotificationTemoignage $notificationTemoignage)
    {
        //
    }
}
