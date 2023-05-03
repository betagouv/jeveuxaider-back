<?php

namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableMissionsDeactivated extends Notification implements ShouldQueue
{
    use Queueable;

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
        $dashboardParticipationsUrl = url(config('app.front_url')."/admin/participations?filter[is_state_pending]=true&filter[ofResponsable]=".$notifiable->id);

        return (new MailMessage)
            ->subject('Vos missions ont été désactivées par un modérateur')
            ->markdown('emails.responsables.missions-deactivated', [
                'notifiable' => $notifiable,
                'dashboardParticipationsUrl' => $dashboardParticipationsUrl
            ])
            ->tag('app-responsable-missions-desactivees');
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
