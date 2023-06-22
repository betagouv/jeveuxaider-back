<?php

namespace App\Listeners;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\App;

class MessageSendingListener
{
    public function handle( MessageSending $event )
    {
        $user = $event->data['notifiable'];
        if ($user->anonymous_at) {
            return false;
        }

        // @todo: si utilisateur est bloqué
    }
}
