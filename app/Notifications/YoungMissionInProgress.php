<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Young;

class YoungMissionInProgress extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Young
     */
    public $young;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Young $young)
    {
        $this->young = $young;
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
            ->subject('Confirmation du lancement de la mission')
            ->greeting('Bonjour ' . $notifiable->full_name . ' !')
            ->line('Vous avez confirmé le lancement de la mission de ' . $this->young->full_name . ' au sein de votre structure dans le cadre du SNU.')
            ->line('Tout au long de la mission, vous pouvez solliciter le service référent en cas de question.')
            ->line('Dès la fin de la mission, nous vous invitons à nous en informer en mettant le dossier du jeune à jour.')
            ->action('Accéder au dossier du jeune', url(config('app.front_app_url') . '/young/' . $this->young->id))
            ->line('En cas d’abandon ou d’exclusion de la mission, nous vous invitons à nous en informer de la même manière.');
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
