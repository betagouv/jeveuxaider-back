<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserBannedInappropriateBehavior extends Notification
{
    use TransactionalEmail;

    public $tag;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tag = 'app-benevole-banni-comportement-inadapte';
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
        return (new MailMessage())
            ->subject('Vous avez été automatiquement désinscrit de la plateforme JeVeuxAider.gouv.fr')
            ->markdown('emails.benevoles.banned-inappropriate-behavior', [
                'notifiable' => $notifiable,
            ])
            ->tag($this->tag);
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
