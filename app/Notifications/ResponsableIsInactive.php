<?php

namespace App\Notifications;

use App\Models\Structure;
use App\Traits\TransactionalEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResponsableIsInactive extends Notification implements ShouldQueue
{
    use TransactionalEmail;
    use Queueable;

    public $tag;
    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure)
    {
        $this->structure = $structure;
        $this->tag = 'app-responsable-inactif';
    }

    public function viaQueues()
    {
        return [
            'mail' => 'low-tasks',
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
            ->subject('Ah, vous voilà ! 💃')
            ->markdown('emails.responsables.inactive', [
                'notifiable' => $notifiable,
                'structure' => $this->structure,
                'urlHome' => $this->trackedUrl('/')
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
            'structure_id' => $this->structure->id,
            'structure_name' => $this->structure->name,
        ];
    }
}
