<?php

namespace App\Notifications;

use App\Notifications\Channels\SmsChannel;
use App\Notifications\Messages\SmsMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class VolunteerHasUnreadMessage extends Notification implements ShouldQueue
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
        $this->message->loadMissing(['conversation', 'from', 'from.profile', 'conversation.conversable.mission.structure']);
        if (empty($this->message->conversation) || empty($this->message->from->profile)) {
            return;
        }

        $smsMaxLength = 160;
        $url = preg_replace("(^https?://(w?)*\.?)", "", url(config('app.front_url') . '/m/' . $this->message->conversation->id));
        $organisationName = $this->message->conversation->conversable->mission->structure->name;
        $content = "Toujours dispo pour aider {$organisationName} ? {$this->message->from->profile->first_name} a besoin de votre retour au + vite : {$url}. A ignorer si vous avez déjà répondu";

        if (mb_strlen($content) > $smsMaxLength) {
            $organisationNameTruncatedLength = mb_strlen($organisationName) - (mb_strlen($content) - ($smsMaxLength - 3));
            if ($organisationNameTruncatedLength >= 5) {
                $organisationNameTruncated = mb_strcut($organisationName, 0, $organisationNameTruncatedLength);
                $content = str_replace($organisationName, $organisationNameTruncated . '...', $content);
            } else {
                $content = str_replace(" {$organisationName}", "", $content);
            }
        }


        return (new SmsMessage())
                ->from('JeVeuxAider')
                ->to($notifiable->profile->mobile)
                ->line($content)
                ->tag('app-benevole-message-non-lu-j-3');
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
