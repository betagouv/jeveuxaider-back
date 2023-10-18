<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class ResponsableHasReplied extends Notification implements ShouldQueue
{
    use Queueable;

    public $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [SmsChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toSms($notifiable)
    {
        $url = preg_replace("(^https?://)", "", url(config('app.front_url') . '/m/' . $this->message->conversation->id));

        return (new SmsMessage())
                ->from('JeVeuxAider')
                ->to($notifiable->profile->mobile)
                ->line("Nouveau message de {$this->message->from->profile->first_name} à propos de votre mission de bénévolat. Répondez au plus vite sur {$url}")
                ->tag('app-responsable-a-repondu');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
