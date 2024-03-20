<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Models\User;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionShared extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;
    public $user;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission, User $user)
    {
        $this->mission = $mission;
        $this->user = $user;
        $this->tag = 'app-mission-shared';
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->trackedUrl($this->mission->full_url);

        return (new MailMessage())
            ->subject($this->user->profile->full_name . ' veut faire du bénévolat avec vous')
            ->markdown('emails.benevoles.mission-shared', [
                'url' => $this->trackedUrl($this->mission->full_url),
                'mission' => $this->mission,
                'user' => $this->user,
            ])
            ->tag($this->tag);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [];
    }
}
