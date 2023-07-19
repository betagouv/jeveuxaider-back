<?php

namespace App\Notifications;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationCanceled extends Notification implements ShouldQueue
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
            ->subject('😔 Oh non… La mission de ' . $this->participation->mission->structure->name . ' a été annulée')
            ->markdown('emails.benevoles.participation-canceled', [
                'url' => $this->participation->conversation ? url(config('app.front_url') . '/messages/'.$this->participation->conversation->id) : url(config('app.front_url') . '/messages'),                'mission' => $this->participation->mission,
                'urlCTA' => url(config('app.front_url') . '/missions-benevolat'),
                'structure' => $this->participation->mission->structure,
                'notifiable' => $notifiable
            ])
            ->tag('app-benevole-participation-canceled');
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
            'mission_id' => $this->participation->mission->id,
            'mission_name' => $this->participation->mission->name,
            'structure_id' => $this->participation->mission->structure->id,
            'structure_name' => $this->participation->mission->structure->name,
        ];
    }
}
