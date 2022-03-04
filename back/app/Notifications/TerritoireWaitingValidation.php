<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Territoire;
use Illuminate\Notifications\Messages\SlackMessage;

class TerritoireWaitingValidation extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Territoire
     */
    public $territoire;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Territoire $territoire)
    {
        $this->territoire = $territoire;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'slack'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('La collectivité "'. $this->territoire->name .'" vient de s\'inscrire. Elle est en attente de validation.')
            ->greeting('Bonjour,')
            ->line('La collectivité "'. $this->territoire->name .'" a rejoint JeVeuxAider.gouv.fr !')
            ->line('Elle est en attente de validation par un modérateur.')
            ->action('Voir la collectivité', url(config('app.front_url') . '/admin/territoires/' . $this->territoire->id))
        ;
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $territoire = $this->territoire;

        return (new SlackMessage)
                    ->from('JeVeuxAider.gouv.fr')
                    ->success()
                    ->to('#collectivités-déploiement')
                    ->content('Une nouvelle collectivité vient de s\'inscrire! Elle est en attente de validation.')
                    ->attachment(function ($attachment) use ($territoire) {
                        $attachment->title($territoire->name, url(config('app.front_url') . '/admin/territoires/' . $territoire->id));
                    });
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
