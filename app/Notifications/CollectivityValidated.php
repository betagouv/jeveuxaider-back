<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Collectivity;

class CollectivityValidated extends Notification
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
        return (new MailMessage)
            ->subject('Votre collectivité "'. $this->collectivity->name .'" a été validée!')
            ->greeting('Bonjour,')
            ->line('**'.$this->collectivity->name . '** a rejoint la Réserve Civique !')
            ->line('Vous avez désormais le rôle de **Responsable Collectivité**.')
            ->line('Connectez-vous au tableau de bord pour visualiser les statistiques dans votre collectivité.')
            ->action('Voir le tableau de bord', url(config('app.url') . '/dashboard'))
        ;
    }
}
