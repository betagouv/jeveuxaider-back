<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisterUserVolontaire extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tag = 'app-benevole-inscription';
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
            ->subject('💪 Une dernière étape pour s’engager ' . $notifiable->profile->first_name . ' !')
            ->markdown('emails.benevoles.inscription', [
                'url' => $this->trackedUrl('/missions-benevolat'),
                'urlDomains' => [
                    'sport' => $this->trackedUrl('/missions-benevolat?domaines=Sport pour tous'),
                    'collecte' => $this->trackedUrl('/missions-benevolat?activities.name=Collecte de produits'),
                    'mentorat' => $this->trackedUrl('/missions-benevolat?activities.name=Mentorat %26 Parrainage'),
                    'nature' => $this->trackedUrl('/missions-benevolat?domaines=Protection de la nature'),
                ],
                'notifiable' => $notifiable
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
