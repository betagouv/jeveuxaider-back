<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Collectivity;

class CollectivityDeclined extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Collectivity
     */
    public $collectivity;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collectivity $collectivity)
    {
        $this->collectivity = $collectivity;
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
            ->subject('Votre collectivité "'. $this->collectivity->name .'" a été refusée.')
            ->greeting('Bonjour,')
            ->line('Votre collectivité "' . $this->collectivity->name . '" a été refusée.')
            ->line('Celle-ci ne répond pas aux critères d\'éligibilités de notre plateforme.')
        ;
    }
}
