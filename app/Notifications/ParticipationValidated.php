<?php

namespace App\Notifications;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationValidated extends Notification implements ShouldQueue
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
        return ['mail', 'database'];
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject('🥳 Bonne nouvelle ! Votre demande de participation est validée')
            ->markdown('emails.benevoles.participation-validated', [
                'url' => $this->participation->conversation ? url(config('app.front_url') . '/messages/'.$this->participation->conversation->id) : url(config('app.front_url') . '/messages'),                'mission' => $this->participation->mission,
                'responsable' => $this->participation->mission->responsable,
                'notifiable' => $notifiable
            ])
            ->tag('app-benevole-participation-validee');
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
            'conversation_id' => $this->participation?->conversation->id,
            'mission_id' => $this->participation->mission->name,
            'mission_name' => $this->participation->mission->name,
            'structure_name' => $this->participation->mission->structure->name,
        ];
    }
}
