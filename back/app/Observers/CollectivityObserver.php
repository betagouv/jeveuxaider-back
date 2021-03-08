<?php

namespace App\Observers;

use App\Models\Collectivity;
use App\Notifications\CollectivityValidated;
use App\Notifications\CollectivityDeclined;
use Illuminate\Support\Facades\Notification;

class CollectivityObserver
{
    public function creating(Collectivity $collectivity)
    {
        if ($collectivity->type == 'commune') {
            $collectivity->setCoordonates();
        }
    }

    public function updating(Collectivity $collectivity)
    {
        if ($collectivity->type == 'commune' && $collectivity->isDirty('zips')) {
            $old = $collectivity->getOriginal('zips') ? $collectivity->getOriginal('zips')[0] : null;
            $new = $collectivity->zips ? $collectivity->zips[0] : null;
            if ($old != $new) {
                if ($new) {
                    $collectivity->setCoordonates();
                }
            }
        }
    }

    public function updated(Collectivity $collectivity)
    {
        $oldState = $collectivity->getOriginal('state');
        $newState = $collectivity->state;

        if ($oldState != $newState) {
            switch ($newState) {
                case 'validated':
                    Notification::send($collectivity->profiles, new CollectivityValidated($collectivity));
                    break;
                case 'refused':
                    Notification::send($collectivity->profiles, new CollectivityDeclined($collectivity));
                    break;
            }
        }
    }
}
