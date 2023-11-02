<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableMissionsDeactivated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $tag;

    public function __construct()
    {
        $this->tag = 'app-responsable-missions-desactivees';
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
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $dashboardParticipationsUrl = "/admin/participations?filter[is_state_pending]=true&filter[ofResponsable]=" . $notifiable->id;

        return (new MailMessage())
            ->subject('Vos missions ont été désactivées')
            ->markdown('emails.responsables.missions-deactivated', [
                'notifiable' => $notifiable,
                'dashboardParticipationsUrl' => $this->trackedUrl($dashboardParticipationsUrl)
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
