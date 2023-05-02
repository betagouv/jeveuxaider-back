<?php

namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionDeactivated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
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
        $dashboardParticipationsUrl = url(config('app.front_url')."/admin/participations?filter[mission.id]=".$this->mission->id."&context_name=".$this->mission->name."&filter[is_state_pending]=true");

        return (new MailMessage)
            ->subject('Votre mission a été désactivée par un modérateur')
            ->markdown('emails.responsables.mission-deactivated', [
                'missionUrl' => url(config('app.front_url').$this->mission->full_url),
                'mission' => $this->mission,
                'notifiable' => $notifiable,
                'dashboardParticipationsUrl' => $dashboardParticipationsUrl
            ])
            ->tag('app-responsable-mission-desactivee');
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
