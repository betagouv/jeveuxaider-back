<?php

namespace App\Notifications;

use App\Models\UserArchivedDatas;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Maize\Encryptable\Encryption;

class SendCodeUnarchiveUserDatas extends Notification
{
    use Queueable;

    private string $email;
    private string $code;
    private string $tag;

    /**
     * Create a new notification instance.
     */
    public function __construct(public UserArchivedDatas $userArchivedDatas)
    {
        $this->email = Encryption::php()->decrypt($this->userArchivedDatas->email);
        $this->code = Encryption::php()->decrypt($this->userArchivedDatas->code);
        $this->tag = 'app-user-activation-code';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage())
            ->subject("Votre code d'activation à usage unique")
            ->markdown('emails.users.unarchive-user-code', [
                'code' => $this->code,
            ])
            ->tag($this->tag);
    }

    public function toSlack(object $notifiable)
    {
        $from = config('app.env') != 'production' ? '[' . config('app.env') . '] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';

        return (new SlackMessage())
            ->from($from)
            ->success()
            ->to('#' . config('services.slack.log_channel'))
            ->content('*' . $this->email . '* a reçu un code d\'activation')
            ->attachment(function ($attachment) {
                $attachment
                    ->color('#BBBBBB')
                    ->fields([
                        'Code' => $this->code,
                    ]);
            });
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
