<?php

namespace App\Notifications;

use App\Models\Message;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageMissionCreated extends Notification implements ShouldQueue
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
        $mission = $this->message->conversation->conversable;
        $structure = $mission->structure;
        $url = $this->message->conversation ? '/messages/' . $this->message->conversation->id : '/messages';
        $isFromResponsable = $structure->members->contains('id', $notifiable->id) ? false : true;

        if ($isFromResponsable) {
            $subject = 'Le responsable de ' . $structure->name . ' vous a répondu !';
            $this->tag = 'app-referent-nouveau-message-mission';
        } else {
            $subject = $this->message->from->profile->full_name . ' souhaite en savoir plus sur votre mission';
            $this->tag = 'app-responsable-nouveau-message-mission';
        }

        return (new MailMessage())
            ->subject($subject)
            ->markdown('emails.benevoles.message-mission', [
                'url' => $this->trackedUrl($url),
                'message' => $this->message,
                'mission' => $mission,
                'structure' => $structure,
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
