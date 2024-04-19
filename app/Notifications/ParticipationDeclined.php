<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationDeclined extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;
    public $message;
    public $reason;
    public $tag;

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
        $this->tag = 'app-benevole-participation-declinee';
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
        $url = $this->participation->conversation ? '/messages/' . $this->participation->conversation->id : '/messages';

        return (new MailMessage())
            ->subject('Quel dommage… votre participation vient d’être déclinée')
            ->markdown('emails.benevoles.participation-declined', [
                'url' => $this->trackedUrl($url),
                'urlSearch' => $this->trackedUrl('/missions-benevolat'),
                'urlQuiz' => $this->trackedUrl('/quiz/generique'),
                'urlMission' => $this->trackedUrl($this->participation->mission->full_url),
                'mission' => $this->participation->mission,
                'structure' => $this->participation->mission->structure,
                'responsable' => $this->participation->mission->responsable,
                'message' => $this->message,
                'reason' => $this->reason,
                'notifiable' => $notifiable
            ])
            ->tag($this->tag);
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
