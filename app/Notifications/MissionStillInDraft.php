<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionStillInDraft extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
        $this->tag = 'app-responsable-mission-restee-en-brouillon';
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
            ->subject('Votre mission est restée au statut “Brouillon”')
            ->markdown('emails.responsables.mission-still-in-draft', [
                'url' => $this->trackedUrl('/admin/missions/' . $this->mission->id . '/edit'),
                'mission' => $this->mission,
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
