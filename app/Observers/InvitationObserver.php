<?php

namespace App\Observers;

use App\Models\Invitation;
use App\Notifications\InvitationSent;

class InvitationObserver
{
    public function created(Invitation $invitation)
    {
        $invitation->notify(new InvitationSent($invitation));
    }
}
