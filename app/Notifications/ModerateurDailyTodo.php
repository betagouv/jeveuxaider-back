<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ModerateurDailyTodo extends Notification
{
    use Queueable;

    public $missionsAndStructuresByDepartment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($missionsAndStructuresByDepartment)
    {
        $this->missionsAndStructuresByDepartment = $missionsAndStructuresByDepartment;
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
            ->subject('Arf on a perdu des référents')
            ->markdown('emails.dailyTodoModerateur', ['items' => $this->missionsAndStructuresByDepartment]);
    }
}
