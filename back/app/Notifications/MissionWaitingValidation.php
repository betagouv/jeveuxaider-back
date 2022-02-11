<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionWaitingValidation extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
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
            ->subject('Votre mission a bien été déposée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Vous avez proposé une mission : ' . $this->mission->name .'.')
            ->line('Cette mission, avant d’être proposée à un ou plusieurs bénévoles, doit être validée par le service en charge des missions proposées sur JeVeuxAider.gouv.fr.')
            ->line('Nous vous informerons sous peu de la validation de la mission que vous avez proposée.')
            ->action('Accéder à mon compte', url(config('app.front_url') . '/dashboard/structure/' . $this->mission->structure->id . '/missions'));
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
