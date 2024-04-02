<?php

namespace App\Notifications;

use App\Models\Territoire;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class TerritoireWaitingValidation extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Territoire
     */
    public $territoire;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Territoire $territoire)
    {
        $this->territoire = $territoire;
        $this->tag = 'app-territoire-en-attente-de-validation';
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
        return (new MailMessage())
            ->subject('La collectivité "' . $this->territoire->name . '" vient de s\'inscrire. Elle est en attente de validation.')
            ->greeting('Bonjour,')
            ->line('La collectivité "' . $this->territoire->name . '" a rejoint JeVeuxAider.gouv.fr !')
            ->line('Elle est en attente de validation par un modérateur.')
            ->action('Voir la collectivité', $this->trackedUrl('/admin/contenus/territoires/' . $this->territoire->id))
            ->tag($this->tag);
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

        return (new SlackMessage())
                    ->from('JeVeuxAider.gouv.fr')
                    ->success()
                    ->to('#déploiement-collectivités-acquisition')
                    ->content('Une nouvelle collectivité vient de s\'inscrire! Elle est en attente de validation.')
                    ->attachment(function ($attachment) use ($territoire) {
                        $attachment->title($territoire->name, url(config('app.front_url') . '/admin/contenus/territoires/' . $territoire->id));
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
