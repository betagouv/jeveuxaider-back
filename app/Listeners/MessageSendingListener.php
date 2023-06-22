<?php

namespace App\Listeners;

use App\Models\User;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\App;

class MessageSendingListener
{
    public function handle( MessageSending $event )
    {
        if (isset($event->data['notifiable']) && $event->data['notifiable'] instanceof User) {
            $user = $event->data['notifiable'];
            if ($user->anonymous_at) {
                return false;
            }
        }

        // @todo: si utilisateur est bloqué
    }
}
