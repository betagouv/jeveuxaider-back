<?php

namespace App\Notifications;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MessageCreated extends Notification implements ShouldQueue
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
        $fromOrga = false;
        if ($this->message->conversation->conversable->mission->responsable_id == $this->message->from->profile->id) {
            $fromOrga = $this->message->conversation->conversable->mission->structure;
        }
        $message = (new MailMessage);
        if ($fromOrga) {
            $message->subject('Nouveau message de la part de '.$this->message->from->profile->first_name.' ('.$fromOrga->name.')')
                ->tag('app-benevole-nouveau-message');
        } else {
            $message->subject('Nouveau message de la part de '.$this->message->from->profile->first_name)
                ->tag('app-organisation-nouveau-message');
        }
        $message->greeting('Bonjour '.$notifiable->profile->first_name.',')
            ->line($this->message->from->profile->full_name.' a répondu à votre message concernant la mission "'.$this->message->conversation->conversable->mission->name.'"')
            ->line('Vous pouvez échanger avec cette personne directement via la messagerie de JeVeuxAider.gouv.fr.');

        $url = $this->message->conversation ? '/messages/'.$this->message->conversation->id : '/messages';
        $message->action('Continuez la conversation', url(config('app.front_url').$url));

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
