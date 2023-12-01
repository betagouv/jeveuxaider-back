<?php

namespace App\Notifications;

use App\Models\User;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserHasExportedDatas extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $user;
    public $type;
    public $count;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, string $type, int $count = null)
    {
        $this->user = $user;
        $this->type = $type;
        $this->count = $count;
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

    public function toSlack($notifiable)
    {
        $from = config('app.env') != 'production' ? '[' . config('app.env') . '] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';

        $content = '*' . $this->user->profile->full_name . '* a exporté *' . $this->count . ' ' . $this->type . '*';

        return (new SlackMessage())
            ->from($from)
            ->success()
            ->to('#' . config('services.slack.log_channel'))
            ->content($content)
            ->attachment(function ($attachment) {
                $attachment
                    ->color('#BBBBBB')
                    ->fields([
                        'roles' => $this->user->roles->pluck('name')->implode(', '),
                        'contextRole' => $this->user->context_role ?? 'null',
                        'contextableType' => $this->user->contextable_type ?? 'null',
                        'contextableId' => $this->user->contextable_id ?? 'null',
                    ]);
            });
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

}
