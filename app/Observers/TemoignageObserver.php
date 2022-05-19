<?php

namespace App\Observers;

use App\Models\NotificationTemoignage;
use App\Models\Temoignage;

class TemoignageObserver
{

    public function creating(Temoignage $temoignage)
    {
        if($temoignage->grade > 3){
            $temoignage->is_published = true;
        }
    }

    public function created(Temoignage $temoignage)
    {
        $temoignage->participation->notificationTemoignage->delete();
    }

    public function updated(Temoignage $temoignage)
    {
        //
    }

    public function saving(Temoignage $temoignage)
    {
        if($temoignage->is_published && $temoignage->grade < 4){
            $temoignage->is_published = false;
        }
    }

    public function deleted(Temoignage $temoignage)
    {
        //
    }

    public function restored(Temoignage $temoignage)
    {
        //
    }

    public function forceDeleted(Temoignage $temoignage)
    {
        //
    }
}
