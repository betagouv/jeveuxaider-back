<?php

namespace App\Notifications;

use App\Models\Message;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageStructureCreated extends Notification implements ShouldQueue
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
        $structure = $this->message->conversation->conversable;
        $url = $this->message->conversation ? '/messages/' . $this->message->conversation->id : '/messages';
        $isFromResponsable = $structure->members->contains('id', $notifiable->id) ? false : true;

        if ($isFromResponsable) {
            $subject = 'Le responsable de ' . $structure->name . ' vous a répondu !';
            $this->tag = 'app-referent-nouveau-message-organisation';
        } else {
            $subject = $this->message->from->profile->full_name . ' souhaite en savoir plus sur votre organisation';
            $this->tag = 'app-responsable-nouveau-message-organisation';
        }

        return (new MailMessage())
            ->subject($subject)
            ->markdown('emails.benevoles.message-structure', [
                'url' => $this->trackedUrl($url),
                'message' => $this->message,
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
