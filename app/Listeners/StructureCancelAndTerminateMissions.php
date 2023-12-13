<?php

namespace App\Listeners;

use App\Events\StructureUnsubscribed;
use App\Models\Mission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StructureCancelAndTerminateMissions implements ShouldQueue
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
        // if($event->structure->has('missions')) {
        //         $missions = $event->structure->missions;

        //         $missions
        //             ->whereIn('state', ['Brouillon', 'En attente de validation', 'En cours de traitement'])
        //             ->each(function ($mission) {
        //                 $mission->update(['state' => 'Annulée']);
        //             });

        //         // Notifs OFF
        //         Mission::where('structure_id', $event->structure->id)
        //             ->outdated()
        //             ->where('state', 'Validée')
        //             ->update(['state' => 'Terminée']);

        //         // Notifs ON
        //         Mission::where('structure_id', $event->structure->id)
        //             ->notOutdated()
        //             ->where('state', 'Validée')
        //             ->each(function ($mission) {
        //                 $mission->update(['state' => 'Annulée']);
        //             });

        //         // $event->structure->missions->unsearchable();
        // }
    }
}
