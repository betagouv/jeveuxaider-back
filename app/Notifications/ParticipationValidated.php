<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;
use Illuminate\Support\HtmlString;

class ParticipationValidated extends Notification
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
            ->subject('Bravo ! Votre demande de participation vient d\'être acceptée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous avons le plaisir de vous annoncer que votre participation à la mission « ' . $this->participation->mission->name .' » a été acceptée !')
            ->line('Vous pouvez dès maintenant contacter votre structure :')
            ->line(
                new HtmlString(
                    $this->participation->mission->tuteur->full_name . '<br>' .
                    $this->participation->mission->tuteur->email
                )
            )
            ->line('Retrouvez les détails dans votre espace utilisateur.')
            ->action('Accéder à mon compte', url(config('app.url')));
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
