<?php

namespace App\Listeners;

use App\Events\StructureUnsubscribed;
use Illuminate\Queue\InteractsWithQueue;

class StructureDetachMembers
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
    public function handle(StructureUnsubscribed $event): void
    {
        // if($event->structure->has('members')) {
        //     $members = $event->structure->members;

        //     $event->structure->members()->detach();

        //     $members->each(function ($user) use ($event) {
        //         if ($user->context_role == 'responsable' && $user->contextable_id == $event->structure->id) {
        //             $user->resetContextRole();
        //         }
        //         $user->notify(new \App\Notifications\StructureUnsubscribed($event->structure));
        //     });
        // }
    }
}
