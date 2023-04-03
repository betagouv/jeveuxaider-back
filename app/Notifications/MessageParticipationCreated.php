<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageParticipationCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
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
        $participation = $this->message->conversation->conversable;
        $isFromResponsable = $participation->mission->responsable_id == $this->message->from->profile->id ? true: false;
        $subject = $isFromResponsable ? 'Nouveau message de la part de ' . $this->message->from->profile->first_name : $this->message->from->profile->first_name . ' vous a envoyé un nouveau message !';
        $tag = $isFromResponsable ? 'app-benevole-nouveau-message' : 'app-organisation-nouveau-message';

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.benevoles.message-participation', [
                'url' => $this->message->conversation ? url(config('app.front_url') . '/messages/'.$this->message->conversation->id) :  url(config('app.front_url') . '/messages'),
                'message' => $this->message,
                'mission' => $participation->mission,
                'structure' => $participation->mission->structure,
                'responsable' => $participation->mission->responsable,
                'from' => $this->message->from->profile,
                'notifiable' => $notifiable,
                'isFromResponsable' => $isFromResponsable
            ])
            ->tag($tag);
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
