<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ParticipationWaitingValidation extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
        $this->tag = 'app-responsable-participation-en-attente-de-validation';
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
        $showInfos = in_array($this->participation->mission->structure_id, [5374]) ? true : false;
        $url = $this->participation->conversation ? '/messages/' . $this->participation->conversation->id : '/messages';

        return (new MailMessage())
            ->subject('Vous avez une nouvelle demande de participation 👊')
            ->markdown('emails.responsables.participation-waiting-validation', [
                'url' => $this->trackedUrl($url),
                'mission' => $this->participation->mission,
                'structure' => $this->participation->mission->structure,
                'benevole' => $this->participation->profile,
                'notifiable' => $notifiable,
                'showInfos' => $showInfos
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
            'benevole_id' => $this->participation->profile->id,
            'benevole_first_name' => $this->participation->profile->first_name,
            'benevole_last_name' => $this->participation->profile->last_name,
            'benevole_zip' => $this->participation->profile->zip,
            'benevole_birthday' => $this->participation->profile->birthday,
            'benevole_type' => $this->participation->profile->type,
            'benevole_picture' => $this->participation->profile?->avatar?->urls,
            'participation_id' => $this->participation->id,
            'participation_state' => $this->participation->state,
            'conversation_id' => $this->participation?->conversation?->id,
            'mission_id' => $this->participation->mission->id,
            'mission_name' => $this->participation->mission->name,
            'structure_id' => $this->participation->mission->structure->id,
            'structure_name' => $this->participation->mission->structure->name,
        ];
    }
}
