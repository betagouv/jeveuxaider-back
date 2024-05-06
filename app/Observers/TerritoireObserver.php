<?php

namespace App\Observers;

use App\Models\Territoire;
use App\Notifications\TerritoireWaitingValidation;
use Illuminate\Support\Facades\Notification;

class TerritoireObserver
{
    public function creating(Territoire $territoire)
    {
        if (in_array($territoire->type, ['city'])) {
            $territoire->setCoordonates();
        }
    }

    public function created(Territoire $territoire)
    {
        if ($territoire->state == 'waiting') {
            Notification::route('slack', config('services.slack.hook_url'))
                ->notify(new TerritoireWaitingValidation($territoire));
        }
    }

    public function updating(Territoire $territoire)
    {
        if (in_array($territoire->type, ['city'])) {
            $oldZip = ! empty($territoire->getOriginal('zips')) ? $territoire->getOriginal('zips')[0] : null;
            $newZip = ! empty($territoire->zips) ? $territoire->zips[0] : null;
            if ($oldZip != $newZip) {
                $territoire->setCoordonates();
            }
        }
    }
}
