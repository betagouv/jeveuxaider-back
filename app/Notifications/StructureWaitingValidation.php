<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureWaitingValidation extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $structure;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($structure)
    {
        $this->structure = $structure;
        $this->tag = 'app-responsable-organisation-en-attente-de-validation';
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
        return (new MailMessage())
            ->subject('Plus que quelques étapes avant de commencer à recruter des bénévoles !')
            ->markdown('emails.responsables.structure-waiting-validation', [
                'url' => $this->trackedUrl('/admin/organisations/' . $this->structure->id . '/missions/add'),
                'urlCharte' => $this->trackedUrl('/charte-reserve-civique'),
                'structure' => $this->structure,
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
