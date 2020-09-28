<?php

namespace App\Observers;

use App\Models\Collectivity;
use App\Notifications\CollectivityValidated;
use App\Notifications\CollectivityDeclined;
use Illuminate\Support\Facades\Notification;

class CollectivityObserver
{
    public function updated(Collectivity $collectivity)
    {
        debug('coucou');
        debug($collectivity->profiles);
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
