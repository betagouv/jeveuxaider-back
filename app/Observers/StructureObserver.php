<?php

namespace App\Observers;

use App\Models\Structure;

class StructureObserver
{
    /**
     * Listen to the Structure created event.
     *
     * @param  \App\Models\Structure  $structure
     * @return void
     */
    public function created(Structure $structure)
    {
        if ($structure->user->profile) {
            $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
        }
    }

    /**
     * Listen to the Structure deleting event.
     *
     * @param  \App\Models\Structure  $structure
     * @return void
     */
    public function deleting(Structure $structure)
    {
        //
    }
}
