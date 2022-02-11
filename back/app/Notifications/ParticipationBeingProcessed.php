<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;

class ParticipationBeingProcessed extends Notification
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
        $message = (new MailMessage)
            ->subject('Votre demande de participation est en cours de traitement')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Votre demande de participation à la mission « ' . $this->participation->mission->name .' » est actuellement en cours de traitement.')
            ->line('Le responsable de la mission vous contactera très prochainement pour un premier échange.')
            ->line('En cas de besoin, vous pouvez le contacter directement via la messagerie de JeVeuxAider.gouv.fr.');

        $url = $this->participation->conversation ? '/messages/' . $this->participation->conversation->id : '/messages';
        $message->action('Accéder à ma messagerie', url(config('app.front_url') . $url));

        return $message;
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
