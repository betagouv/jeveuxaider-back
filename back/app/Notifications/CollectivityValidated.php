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
            ->greeting('Bonjour '. $notifiable->first_name .',')
            ->line('Le compte  **'.$this->collectivity->name . '** a été validé et rejoint JeVeuxAider.gouv.fr.')
            ->line('Vous pouvez désormais vous connecter à votre espace pour :')
            ->line('- Editer les éléments visuels et textuels de votre page (une page personnalisée est une page engagée !')
            ->line('- Visualiser les statistiques de votre collectivité')
            ->line('- Publier des missions de bénévolat')
            ->action('Voir le tableau de bord', url(config('app.url') . '/dashboard'))
        ;
    }
}
