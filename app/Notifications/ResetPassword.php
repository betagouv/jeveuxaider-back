<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordNotification;

class ResetPassword extends ResetPasswordNotification
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link =  config('app.front_app_url') . '/password/reset/' . $this->token . '?email=' . $notifiable->getEmailForPasswordReset();

        return (new MailMessage)
                    ->subject('Réinitialiser mon mot de passe')
                    ->greeting('Réinitialiser mon mot de passe')
                    ->line('Vous recevez cet email car nous avons reçu une demande de réinitialisation du mot de passe pour votre compte.')
                    ->action('Réinitialiser mon mot de passe', $link)
                    ->line('Ce lien de réinitialisation de mot de passe expirera dans 60 minutes.')
                    ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune autre action n\'est requise.');
    }
}
