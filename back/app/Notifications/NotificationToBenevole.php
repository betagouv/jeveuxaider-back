<?php

namespace App\Notifications;

use App\Models\NotificationBenevole;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotificationToBenevole extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var NotificationBenevole
     */
    public $notificationBenevole;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationBenevole $notificationBenevole)
    {
        $this->notificationBenevole = $notificationBenevole;
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
            ->subject("{$this->notificationBenevole->mission->structure->name} vous propose une mission de bénévolat")
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line("L'organisation {$this->notificationBenevole->mission->structure->name} vous propose une nouvelle mission de bénévolat dans le domaine d'action **{$this->notificationBenevole->mission->domaines[0]->name}**.")
            ->line("Votre profil correspond à celui des bénévoles recherchés.")
            ->line("")
            ->line("La mission :")
            ->line("**{$this->notificationBenevole->mission->name}**")
            ->action('Proposer votre aide', url(config('app.url')."/missions/{$this->notificationBenevole->mission->id}/{$this->notificationBenevole->mission->slug}/?utm_source=mktplace"))
            ->line('Nous comptons sur vous pour faire vivre l’engagement. Merci !');
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
