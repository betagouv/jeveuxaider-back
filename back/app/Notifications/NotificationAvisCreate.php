<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\NotificationAvis;

class NotificationAvisCreate extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var NotificationAvis
     */
    public $notificationAvis;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationAvis $notificationAvis)
    {
        $this->notificationAvis = $notificationAvis;
        $this->participation = $this->notificationAvis->participation;
        $this->mission = $this->participation->mission;
        $this->profile = $this->participation->profile;
        $this->structure = $this->mission->structure;
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
            ->subject("Comment s'est déroulée votre mission ?")
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line("La mission «" . $this->mission->name . "» est désormais finie ! " . $this->structure->name . " et toute l'équipe de JVA tenons à vous remercier pour votre engagement.")
            ->line("Prenez désormais le temps de nous raconter votre expérience 😉")
            ->action('Raconter mon expérience', url(config('app.url') . '/temoignages/' . $this->notificationAvis->token))
            ->line("À bientôt sur JeVeuxAider.gouv.fr !");
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
