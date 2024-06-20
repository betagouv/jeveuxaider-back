<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationDeclinedFromMissionTerminated extends Notification implements ShouldQueue
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
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
        $this->tag = 'app-benevole-participation-declinee-mission-terminated';
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
        $firstResponsable = $this->participation->mission->responsables->first();

        return (new MailMessage())
            ->subject('La mission à laquelle vous avez candidaté est terminée')
            ->markdown('emails.benevoles.participation-declined-mission-terminated', [
                'url' => $this->trackedUrl($url),
                'urlSearch' => $this->trackedUrl('/missions-benevolat'),
                'urlQuiz' => $this->trackedUrl('/quiz/generique'),
                'mission' => $this->participation->mission,
                'structure' => $this->participation->mission->structure,
                'responsable' => $firstResponsable,
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
