<?php

namespace App\Notifications;

use App\Models\NotificationBenevole;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationToBenevole extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var NotificationBenevole
     */
    public $notificationBenevole;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationBenevole $notificationBenevole)
    {
        $this->notificationBenevole = $notificationBenevole;
        $this->tag = 'app-benevole-proposition-mission';
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
        $this->notificationBenevole->mission->loadMissing(['domaine', 'template.domaine']);
        $domaine = $this->notificationBenevole->mission->template_id ? $this->notificationBenevole->mission->template->domaine : $this->notificationBenevole->mission->domaine;
        $url = "/missions-benevolat/{$this->notificationBenevole->mission->id}/{$this->notificationBenevole->mission->slug}?utm_source=mktplace";
        $structure = $this->notificationBenevole->mission->structure;
        $mission = $this->notificationBenevole->mission;

        return (new MailMessage())
            ->subject("{$structure->name} vous propose une mission de bénévolat")
            ->markdown('emails.benevoles.proposition-mission', [
                'url' => $this->trackedUrl($url),
                'urlProfilePreferences' => $this->trackedUrl('/profile/preferences'),
                'domaine' => $domaine,
                'structure' => $structure,
                'mission' => $mission,
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
