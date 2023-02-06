<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BenevoleCejOneYearAfter extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject($notifiable->first_name.', êtes-vous toujours en Contrat d’Engagement Jeune ?')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line(new HtmlString('Lors de votre inscription sur <a href="'.url(config('app.front_url')).'">JeVeuxAider.gouv.fr</a>, vous nous avez indiqué être accompagné dans le cadre du Contrat d’Engagement Jeune.'))
            ->line('Si vous n’êtes plus accompagné dans le cadre de ce dispositif, nous vous invitons à mettre à jour votre profil. Promis, cela ne prend que 2 minutes !')
            ->action('Je mets à jour mon profil', url(config('app.front_url').'/profile/edit'))
            ->line('Si besoin, vous pouvez contacter le support utilisateurs par simple retour de mail.');
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
