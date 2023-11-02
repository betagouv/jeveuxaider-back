<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReseauNewLead extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $form;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($form)
    {
        $this->form = $form;
        $this->tag = 'app-nouveau-lead-reseau';
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
            ->subject('Nouveau Lead Tête de réseau !')
            ->markdown('emails.reseauNewLead', ['form' => $this->form])
            ->tag($this->tag);
    }
}
