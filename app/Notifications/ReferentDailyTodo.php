<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReferentDailyTodo extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $missions;
    public $structures;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($missions, $structures)
    {
        $this->missions = $missions;
        $this->structures = $structures;
        $this->tag = 'app-referent-daily-todo';
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
            ->subject('Ça bouge dans votre département !')
            ->markdown('emails.bilans.referent-daily-todo', [
                'notifiable' => $notifiable,
                'url' => $this->trackedUrl('/dashboard'),
                'variables' => [
                    'newMissionsCount' => count($this->missions),
                    'newStructuresCount' => count($this->structures),
                ],
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
