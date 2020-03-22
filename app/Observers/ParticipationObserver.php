<?php

namespace App\Observers;

use App\Models\Participation;

class ParticipationObserver
{
    public function created(Participation $participation)
    {
        // dump("coucou");
    }
}
