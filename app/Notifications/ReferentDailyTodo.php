<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReferentDailyTodo extends Notification
{
    use Queueable;

    public $missions;

    public $structures;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($missions, $structures)
    {
        $this->missions = $missions;
        $this->structures = $structures;
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
            ->subject('Ça bouge dans votre département !')
            ->markdown('emails.bilans.referent-daily-todo', [
                'notifiable' => $notifiable,
                'url' => url(config('app.front_url') . '/dashboard'),
                'variables' => [
                    'newMissionsCount' => count($this->missions),
                    'newStructuresCount' => count($this->structures),
                ],
            ])
            ->tag('app-referent-daily-todo');
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
