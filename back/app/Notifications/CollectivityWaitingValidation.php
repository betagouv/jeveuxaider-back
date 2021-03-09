<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Collectivity;
use Illuminate\Notifications\Messages\SlackMessage;

class CollectivityWaitingValidation extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Collectivity
     */
    public $collectivity;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Collectivity $collectivity)
    {
        $this->collectivity = $collectivity;
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
            ->subject('La collectivité "'. $this->collectivity->name .'" vient de s\'inscrire. Elle est en attente de validation.')
            ->greeting('Bonjour,')
            ->line('La collectivité "'. $this->collectivity->name .'" a rejoint JeVeuxAider.gouv.fr !')
            ->line('Elle est en attente de validation par un modérateur.')
            ->action('Voir la collectivité', url(config('app.url') . '/dashboard/collectivity/' . $this->collectivity->id . '/edit'))
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
        $collectivity = $this->collectivity;

        return (new SlackMessage)
                    ->from('JeVeuxAider.gouv.fr')
                    ->success()
                    ->to('#collectivités-déploiement')
                    ->content('Une nouvelle collectivité vient de s\'inscrire! Elle est en attente de validation.')
                    ->attachment(function ($attachment) use ($collectivity) {
                        $attachment->title($collectivity->name, url(config('app.url') . '/dashboard/collectivity/' . $collectivity->id . '/edit'));
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
