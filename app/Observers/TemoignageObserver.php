<?php

namespace App\Observers;

use App\Models\Temoignage;

class TemoignageObserver
{
    public function creating(Temoignage $temoignage)
    {
    }

    public function created(Temoignage $temoignage)
    {
        $temoignage->participation->notificationTemoignage?->delete();
        $temoignage->participation->mission->structure->calculateScore();
    }

    public function updated(Temoignage $temoignage)
    {
        $temoignage->participation->mission->structure->calculateScore();
    }

    public function saving(Temoignage $temoignage)
    {
        if ($temoignage->is_published && $temoignage->grade < 4) {
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
