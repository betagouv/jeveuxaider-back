<?php

namespace App\Notifications;

use App\Models\NotificationTemoignage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationTemoignageCreate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var NotificationTemoignage
     */
    public $notificationTemoignage;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    public $mission;

    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationTemoignage $notificationTemoignage)
    {
        $this->notificationTemoignage = $notificationTemoignage;
        $this->participation = $this->notificationTemoignage->participation;
        $this->mission = $this->participation->mission;
        $this->structure = $this->mission->structure;
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
        return (new MailMessage)
            ->subject($notifiable->profile->first_name . ', comment s’est passée votre mission ?')
            ->markdown('emails.benevoles.mission-over', [
                'url' => url(config('app.front_url') . '/temoignages/' . $this->notificationTemoignage->token),
                'mission' => $this->mission,
                'organisation' =>  $this->structure,
                'notifiable' => $notifiable
            ])
            ->tag('app-benevole-mission-over-temoignage');
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
