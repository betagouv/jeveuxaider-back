<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;

class ParticipationBenevoleCanceled extends Notification
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
            ->subject('Une participation a été annulée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line($this->participation->profile->full_name . ' a annulée sa participation à la mission « ' . $this->participation->mission->name . ' ».');

        if ($this->reason && $this->reason != 'other') {
            $message->line('La raison est la suivante : ' . config('taxonomies.participation_canceled_by_benevole_reasons.terms')[$this->reason]);
        }

        $url = $this->participation->conversation ? '/messages/' . $this->participation->conversation->id : '/messages';
        $message->action('Accéder à ma messagerie', url(config('app.url') . $url));

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
