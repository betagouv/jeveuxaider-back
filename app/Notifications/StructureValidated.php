<?php

namespace App\Notifications;

use App\Models\Structure;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureValidated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Structure
     */
    public $structure;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure)
    {
        $this->structure = $structure;
        $this->tag = 'app-responsable-organisation-validee';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
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
            ->subject('Vos missions sont désormais visibles par les bénévoles')
            ->markdown('emails.responsables.structure-validated', [
                'url' => $this->trackedUrl('/admin/organisations/' . $this->structure->id . '/missions/add'),
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
            'structure_id' => $this->structure->id,
            'structure_name' => $this->structure->name,
        ];
    }
}
