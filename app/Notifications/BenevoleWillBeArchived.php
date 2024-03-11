<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class BenevoleWillBeArchived extends Notification implements ShouldQueue
{
    use TransactionalEmail;
    use Queueable;

    public $tag;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->tag = 'app-benevole-archivage';
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
            'mail' => 'low-tasks',
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
            ->subject('On ne va pas se quitter comme ça 😢')
            ->markdown('emails.benevoles.will-be-archived', [
                'notifiable' => $notifiable,
                'url' => $this->trackedUrl('/')
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
