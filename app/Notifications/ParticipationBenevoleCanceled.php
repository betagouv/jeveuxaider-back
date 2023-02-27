<?php

namespace App\Notifications;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationBenevoleCanceled extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;
    public $message;
    public $reason;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation, $message, $reason)
    {
        $this->participation = $participation;
        $this->message = $message;
        $this->reason = $reason;
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject('😔 Oh non… '.$this->participation->profile->full_name.' a annulé sa participation')
            ->markdown('emails.responsables.participation-canceled', [
                'url' => $this->participation->conversation ? url(config('app.front_url') . '/messages/'.$this->participation->conversation->id) : url(config('app.front_url') . '/messages'),                'mission' => $this->participation->mission,
                'benevole' => $this->participation->profile,
                'message' => $this->message && $this->message != '' ? $this->message : null,
                'reason' => $this->reason && $this->reason != 'other' ? config('taxonomies.participation_canceled_by_benevole_reasons.terms')[$this->reason] : null,
                'notifiable' => $notifiable
            ])
            ->tag('app-responsable-participation-annulee-par-benevole');
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
