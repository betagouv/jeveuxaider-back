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
        return ['mail', 'database'];
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
            'benevole_id' => $this->participation->profile->id,
            'benevole_first_name' => $this->participation->profile->first_name,
            'benevole_last_name' => $this->participation->profile->last_name,
            'benevole_zip' => $this->participation->profile->zip,
            'benevole_birthday' => $this->participation->profile->birthday,
            'benevole_type' => $this->participation->profile->type,
            'benevole_picture' => $this->participation->profile?->avatar?->urls,
            'participation_id' => $this->participation->id,
            'participation_state' => $this->participation->state,
            'conversation_id' => $this->participation?->conversation?->id,
            'mission_id' => $this->participation->mission->id,
            'mission_name' => $this->participation->mission->name,
            'structure_id' => $this->participation->mission->structure->id,
            'structure_name' => $this->participation->mission->structure->name,
        ];
    }
}
