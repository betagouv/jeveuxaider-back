<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;

class StructureSubmitted extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Structure
     */
    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure)
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
            ->subject('Une structure est en attente de validation')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('La structure « ' . $this->structure->name . ' » vient de s\'inscrire dans votre département et est en attente de modération.')
            ->line('Les missions proposées par cette structure ne seront publiées qu\'après validation de la structure.')
            ->line('Pour valider ou signaler cette structure, rendez vous dans votre espace référent.')
            ->action('Mon espace référent', url(config('app.url').'/dashboard/structures?filter[state]=En attente de validation'));
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
