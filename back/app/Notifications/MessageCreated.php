<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

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
        $fromOrga = false;
        if($this->message->conversation->conversable->mission->responsable_id == $this->message->from->profile->id) {
            $fromOrga = $this->message->conversation->conversable->mission->structure;
        }
        $message = (new MailMessage);
        if($fromOrga) {
            $message->subject('Nouveau message de la part de ' . $this->message->from->profile->first_name . ' ('.$fromOrga->name.')');
        }
        else {
            $message->subject('Nouveau message de la part de ' . $this->message->from->profile->first_name);
        }
        $message->greeting('Bonjour ' . $notifiable->profile->first_name . ',')
            ->line($this->message->from->profile->full_name .' a répondu à votre message concernant la mission "' . $this->message->conversation->conversable->mission->name . '"')
            ->line('Vous pouvez échanger avec cette personne directement via la messagerie de JeVeuxAider.gouv.fr.');

        $url = $this->message->conversation ? '/messages/' . $this->message->conversation->id : '/messages';
        $message->action('Continuez la conversation sur JeVeuxAider', url(config('app.url') . $url));

        return $message;
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
