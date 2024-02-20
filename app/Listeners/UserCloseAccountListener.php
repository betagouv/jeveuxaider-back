<?php

namespace App\Listeners;

use App\Jobs\AnonymizeUser;
use App\Jobs\SendinblueDeleteUser;
use App\Jobs\UserCancelWaitingParticipations;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Bus;

class UserCloseAccountListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        if(!$event->user){
            return;
        }

        // Bus chain
        Bus::chain([
            new UserCancelWaitingParticipations($event->user),
            new SendinblueDeleteUser($event->user),
            new AnonymizeUser($event->user, 'archived.fr'),
        ])->dispatch();

    }
}
