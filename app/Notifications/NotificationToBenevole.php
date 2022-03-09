<?php

namespace App\Notifications;

use App\Models\NotificationBenevole;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationToBenevole extends Notification implements ShouldQueue
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
        $this->notificationBenevole->mission->load(['domaine', 'template.domaine']);
        $domaine = $this->notificationBenevole->mission->template_id ? $this->notificationBenevole->mission->template->domaine : $this->notificationBenevole->mission->domaine;

        return (new MailMessage)
            ->subject("{$this->notificationBenevole->mission->structure->name} vous propose une mission de bénévolat")
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line("L'organisation {$this->notificationBenevole->mission->structure->name} vous propose une nouvelle mission de bénévolat dans le domaine d'action **{$domaine->name}**.")
            ->line("Votre profil correspond à celui des bénévoles recherchés.")
            ->line("")
            ->line("La mission :")
            ->line("**{$this->notificationBenevole->mission->name}**")
            ->action('Proposer votre aide', url(config('app.front_url')."/missions/{$this->notificationBenevole->mission->id}/{$this->notificationBenevole->mission->slug}/?utm_source=mktplace"))
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
