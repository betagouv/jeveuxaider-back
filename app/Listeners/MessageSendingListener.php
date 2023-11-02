<?php

namespace App\Listeners;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Mail\Events\MessageSending;

class MessageSendingListener
{
    public function handle( MessageSending $event )
    {
        if (isset($event->data['notifiable'])) {
            if ($event->data['notifiable'] instanceof User) {
                $user = $event->data['notifiable'];
            }
            elseif ($event->data['notifiable'] instanceof Profile) {
                $user = $event->data['notifiable']->user;
            }

            if ($user && ($user->anonymous_at || $user->banned_at)) {
                return false;
            }
        }
    }
}
