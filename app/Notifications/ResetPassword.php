<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPassword extends ResetPasswordNotification
{
    use TransactionalEmail;

    public $tag;

    public function __construct($token)
    {
        parent::__construct($token);
        $this->tag = 'app-reset-password';
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = '/password-reset/' . $this->token . '?email=' . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage())
            ->subject('Réinitialiser mon mot de passe')
            ->greeting('Réinitialiser mon mot de passe')
            ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.')
            ->action('Réinitialiser mon mot de passe', $this->trackedUrl($link))
            ->line('Ce lien de réinitialisation de mot de passe expirera dans 3 heures.')
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune autre action n\'est requise.')
            ->tag($this->tag);
    }

    public function toArray($notifiable)
    {

        return [
            //
        ];
    }
}
