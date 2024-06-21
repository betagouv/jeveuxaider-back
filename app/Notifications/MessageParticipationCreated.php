<?php

namespace App\Notifications;

use App\Models\Message;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageParticipationCreated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $message;
    public $tag;

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
        $url = $this->message->conversation ? '/messages/' . $this->message->conversation->id : '/messages';

        $isFromResponsable = $participation->profile->id !== $this->message->from->profile->id;

        if ($isFromResponsable) {
            $subject = 'Nouveau message de la part de ' . $this->message->from->profile->first_name;
            $this->tag = 'app-benevole-nouveau-message';
        } else {
            $subject = $this->message->from->profile->first_name . ' vous a envoyé un nouveau message !';
            $this->tag = 'app-organisation-nouveau-message';
        }

        return (new MailMessage())
            ->subject($subject)
            ->markdown('emails.benevoles.message-participation', [
                'url' => $this->trackedUrl($url),
                'message' => $this->message,
                'mission' => $participation->mission,
                'structure' => $participation->mission->structure,
                'from' => $this->message->from->profile,
                'notifiable' => $notifiable,
                'isFromResponsable' => $isFromResponsable
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
            //
        ];
    }
}
