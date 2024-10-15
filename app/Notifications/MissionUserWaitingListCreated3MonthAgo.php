<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionUserWaitingListCreated3MonthAgo extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    /**
     * Collection of missions that are available.
     *
     * @var Collection|Mission[]
     */
    public Collection $proposedMissions;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
        $this->proposedMissions = Mission::with([
            'structure:id,name,state,statut_juridique,description,slug',
            'template:id,title,subtitle,description,objectif,domaine_id,domaine_secondary_id,activity_id,activity_secondary_id',
            'template.domaine:id,name,slug',
            'template.domaineSecondary:id,name,slug',
            'domaine:id,name,slug',
            'domaineSecondary:id,name,slug',
            'template.photo',
            'illustrations',
            'structure.logo',
            'activity:id,name',
            'activitySecondary:id,name',
            'template.activity:id,name',
            'template.activitySecondary:id,name'
        ])
            ->similarTo($this->mission)
            ->available()
            ->take(3)
            ->get();
        $this->tag = 'app-benevole-mission-user-waiting-list-created-3-month-ago';
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
        if ($this->proposedMissions->isEmpty()) {
            // Skip sending the email if there are no proposed missions
            return null;
        }

        return (new MailMessage())
            ->subject('Un bénévole aimerait proposer son aide pour votre mission')
            ->markdown('emails.benevoles.mission-user-waiting-list-created-3-months-ago', [
                'url' => $this->trackedUrl('/missions-benevolat'),
                'missionUrl' => $this->trackedUrl($this->mission->full_url),
                'quizUrl' => $this->trackedUrl('/quiz/generique'),
                'mission' => $this->mission,
                'proposedMissions' => $this->proposedMissions,
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
            'mission_id' => $this->mission->id,
            'mission_name' => $this->mission->name,
            'structure_id' => $this->mission->structure_id,
        ];
    }
}
