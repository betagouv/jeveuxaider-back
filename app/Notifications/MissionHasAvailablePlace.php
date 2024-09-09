<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionHasAvailablePlace extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
        $this->tag = 'app-benevole-mission-has-available-place';
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
        return (new MailMessage())
            ->subject('🙌 Ça y est ! Vous pouvez proposer votre aide !')
            ->markdown('emails.benevoles.mission-has-available-place', [
                'url' => $this->trackedUrl($this->mission->full_url),
                'urlMessages' => $this->trackedUrl('/messages'),
                'mission' => $this->mission,
                'notifiable' => $notifiable
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
        return [
            'mission_id' => $this->mission->id,
            'mission_name' => $this->mission->name,
            'structure_id' => $this->mission->structure_id,
        ];
    }
}
