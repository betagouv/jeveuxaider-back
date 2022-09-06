<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class BulkOperationsParticipationsValidated extends Notification
{
    public $ids;

    public $currentUser;

    public function __construct($ids, $userId)
    {
        $this->ids = $ids;
        $this->currentUser = User::find($userId);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $ids = $this->ids;
        $currentUser = $this->currentUser;

        return (new SlackMessage)
            ->from('JeVeuxAider.gouv.fr')
            ->success()
            ->to('#bulk-operation')
            ->attachment(function ($attachment) use ($ids, $currentUser) {
                $url = url(config('app.front_url')).'/admin/participations?filter[id]='.implode(',', $ids);
                $urlProfile = url(config('app.front_url')).'/admin/utilisateurs/'.$currentUser->profile->id;
                $attachment->color('#2FB887')->content('<'.$urlProfile.'|'.$currentUser->profile->full_name.'> a *validé* <'.$url.'|'.count($ids)." participation(s)>\n".implode(', ', $ids));
            });
    }
}
