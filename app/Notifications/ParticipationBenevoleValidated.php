<?php

namespace App\Notifications;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationBenevoleValidated extends Notification implements ShouldQueue
{
    use Queueable;

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
            ->subject('Nouvelle participation validée ✔️')
            ->markdown('emails.responsables.participation-validated-by-benevole', [
                'url' => $this->participation->conversation ?
                    url(config('app.front_url') . '/messages/' . $this->participation->conversation->id) :
                    url(config('app.front_url') . '/messages'),
                'mission' => $this->participation->mission,
                'benevole' => $this->participation->profile,
                'notifiable' => $notifiable
            ])
            ->tag('app-responsable-participation-validee-par-benevole');
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
