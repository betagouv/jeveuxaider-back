<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageMissionCreated extends Notification implements ShouldQueue
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
        $mission = $this->message->conversation->conversable;
        $structure = $mission->structure;
        $isFromResponsable = $structure->members->contains('id', $notifiable->id) ? false : true;
        $subject = $isFromResponsable ? 'Le responsable de '. $structure->name .' vous a répondu !' : $this->message->from->profile->full_name . ' souhaite en savoir plus sur votre mission';
        $tag = $isFromResponsable ? 'app-referent-nouveau-message-mission' : 'app-responsable-nouveau-message-mission';

        return (new MailMessage)
            ->subject($subject)
            ->markdown('emails.benevoles.message-mission', [
                'url' => url(config('app.front_url') . $this->message->conversation ? '/messages/'.$this->message->conversation->id : '/messages'),
                'message' => $this->message,
                'mission' => $mission,
                'structure' => $structure,
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
