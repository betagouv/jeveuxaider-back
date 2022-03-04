<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;

class ParticipationDeclined extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;
    public $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation, $reason)
    {
        $this->participation = $participation;
        $this->reason = $reason;
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
            ->subject('Votre participation a été déclinée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Nous avons bien reçu votre candidature pour une mission au sein de l\'organisation ' . $this->participation->mission->structure->name. '.')
            ->line("Malheureusement, l'organisation ne pourra pas vous accueillir en mission de bénévolat.");

        if ($this->reason && $this->reason != 'other') {
            $message->line('La raison est la suivante: '. config('taxonomies.participation_declined_reasons.terms')[$this->reason]);
        }

        $url = $this->participation->conversation ? '/messages/' . $this->participation->conversation->id : '/messages';
        $message->action('Accéder à ma messagerie', url(config('app.front_url') . $url));

        $message->line('Encore merci pour votre engagement.');

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
