<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParticipationCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
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
            ->subject('Votre participation a été annulée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous sommes au regret de vous informer que votre participation à la mission « ' . $this->participation->mission->name . ' » est annulée.')
            ->line('Nous vous invitons à proposer votre aide sur une autre mission disponible sur JeVeuxAider.gouv.fr')
            ->action('Toutes nos missions', url(config('app.front_url') . '/missions-benevolat'))
        ;
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
