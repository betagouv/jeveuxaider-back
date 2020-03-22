<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;
use Illuminate\Support\HtmlString;

class ParticipationWaitingValidation extends Notification
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
        return (new MailMessage)
            ->subject('Vous avez une nouvelle demande de participation')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Bonne nouvelle ! ' . $this->participation->profile->full_name .' souhaite participer à la missions « ' . $this->participation->mission->name .' »')
            ->line('Voici ses coordonnées :')
            ->line(
                new HtmlString(
                    $this->participation->profile->full_name . '<br>' .
                    $this->participation->profile->mobile . '<br>' .
                    $this->participation->profile->email
                )
            )
            ->line('Merci de confirmer sa participation depuis votre espace de gestion.')
            ->action('Gérer mes participations', url(config('app.url').'/dashboard/participations'));
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
