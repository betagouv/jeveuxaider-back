<?php

namespace App\Listeners;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Mail\Events\MessageSending;

class MessageSendingListener
{
    public function handle(MessageSending $event)
    {
        if (isset($event->data['notifiable'])) {
            $notifiable = $event->data['notifiable'];
            if ($notifiable instanceof User) {
                $user = $notifiable;
            } elseif ($notifiable instanceof Profile) {
                $user = $notifiable->user;
            }

            if ($user && !$user->canBeNotified()) {
                return false;
            }
        }
    }
}
