<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BenevoleCejSixMonthsAfter extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tag = 'app-cej-six-month-after';
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
        return (new MailMessage())
            ->subject($notifiable->first_name . ', êtes-vous toujours en Contrat d’Engagement Jeune ?')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line(new HtmlString('Lors de votre inscription sur <a href="' . $this->trackedUrl('') . '">JeVeuxAider.gouv.fr</a>, vous nous avez indiqué être accompagné dans le cadre du Contrat d’Engagement Jeune.'))
            ->line('Si vous n’êtes plus accompagné dans le cadre de ce dispositif, nous vous invitons à mettre à jour votre profil. Promis, cela ne prend que 2 minutes !')
            ->action('Mettre à jour mon profil', $this->trackedUrl('/profile/edit'))
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
