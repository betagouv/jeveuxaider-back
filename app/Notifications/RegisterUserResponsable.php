<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RegisterUserResponsable extends Notification
{
    use Queueable;

    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($structure)
    {
        $this->structure = $structure;
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
            ->subject('Votre organisation est en cours de validation')
            ->greeting('Bonjour ' . $notifiable->profile->first_name . ',')
            ->line('Vous vous êtes inscrit sur la plateforme de dépôt de missions de la Réserve Civique.')
            ->line('Votre organisation « ' . $this->structure->name . ' » est en cours de validation.')
            ->line('Vous pouvez désormais proposer des missions de volontariat qui seront visibles sur la plateforme une fois votre organisation validée.')
            ->action('Créer une mission', url(config('app.url') . '/dashboard/structure/' . $this->structure->id . '/missions/add'));
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
