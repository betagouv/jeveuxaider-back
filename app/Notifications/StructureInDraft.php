<?php

namespace App\Notifications;

use App\Models\Structure;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureInDraft extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $structure;
    public $template;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure, $template)
    {
        $this->structure = $structure;
        $this->template = $template;
        $this->tag = 'app-responsable-organisation-en-brouillon';
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
            ->subject($notifiable->profile->first_name . ', recrutez des bénévoles en 2 minutes')
            ->markdown('emails.responsables.structure-in-draft', [
                'url' => $this->trackedUrl('/admin/organisations/' . $this->structure->id . '/edit'),
                'template' => $this->template,
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
