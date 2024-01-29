<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionDeactivated extends Notification implements ShouldQueue
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
        $this->tag = 'app-responsable-mission-desactivee';
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
        $dashboardParticipationsUrl = $this->trackedUrl("/admin/participations?filter[mission.id]=" . $this->mission->id . "&context_name=" . $this->mission->name . "&filter[state]=En+cours+de+traitement,En+attente+de+validation");

        return (new MailMessage())
            ->subject('Votre mission a été désactivée')
            ->markdown('emails.responsables.mission-deactivated', [
                'missionUrl' => $this->trackedUrl($this->mission->full_url),
                'mission' => $this->mission,
                'notifiable' => $notifiable,
                'dashboardParticipationsUrl' => $dashboardParticipationsUrl
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
            'mission_id' => $this->mission->id,
            'mission_name' => $this->mission->name,
            'structure_id' => $this->mission->structure_id,
        ];
    }
}
