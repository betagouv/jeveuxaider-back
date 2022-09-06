<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class BulkOperationsParticipationsDeclined extends Notification
{
    public $ids;

    public $currentUser;

    public $reason;

    public $content;

    public function __construct($ids, $userId, $reason, $content)
    {
        $this->ids = $ids;
        $this->currentUser = User::find($userId);
        $this->reason = $reason;
        $this->content = $content;
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
        $reason = $this->reason;
        $content = $this->content;

        return (new SlackMessage)
            ->from('JeVeuxAider.gouv.fr')
            ->success()
            ->to('#bulk-operation')
            ->attachment(function ($attachment) use ($ids, $currentUser, $reason, $content) {
                $url = url(config('app.front_url')).'/admin/participations?filter[id]='.implode(',', $ids);
                $urlProfile = url(config('app.front_url')).'/admin/utilisateurs/'.$currentUser->profile->id;
                $precisions = empty($content) ? '-' : $content;
                $attachment
                    ->color('#FF0000')
                    ->content('<'.$urlProfile.'|'.$currentUser->profile->full_name.'> a *refusé* <'.$url.'|'.count($ids)." participation(s)>\n".implode(', ', $ids)."\n*Raison:* ".$reason."\n*Précisions:* ".$precisions);
            });
    }
}
