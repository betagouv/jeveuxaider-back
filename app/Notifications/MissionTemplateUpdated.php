<?php

namespace App\Notifications;

use App\Models\MissionTemplate;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MissionTemplateUpdated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    public $missionTemplate;

    public $oldMissionTemplate;

    public $changes;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionTemplate $missionTemplate, $oldMissionTemplate, $changes)
    {
        $this->missionTemplate = $missionTemplate;
        $this->oldMissionTemplate = $oldMissionTemplate;
        $this->changes = $changes;
        $this->tag = 'app-maj-modele-mission';
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
        $message = (new MailMessage())
            ->subject($this->missionTemplate->reseau->name . ' : Un modèle de mission a été modifié après validation')
            ->greeting('Bonjour,')
            ->line('Le réseau **' . $this->missionTemplate->reseau->name . '** a modifié son modèle de mission : **' . $this->missionTemplate->title . '**');

        if (isset($this->changes['title'])) {
            $message->line('#### Titre (avant / après)');
            $message->line($this->oldMissionTemplate['title']);
            $message->line($this->changes['title']);
        }

        if (isset($this->changes['subtitle'])) {
            $message->line('#### Titre court (avant / après)');
            $message->line($this->oldMissionTemplate['subtitle']);
            $message->line($this->changes['subtitle']);
        }

        if (isset($this->changes['description'])) {
            $message->line('#### Description (avant / après)');
            $message->line(new HtmlString($this->oldMissionTemplate['description']));
            $message->line(new HtmlString($this->changes['description']));
        }

        if (isset($this->changes['objectif'])) {
            $message->line('#### Objectif (avant / après)');
            $message->line(new HtmlString($this->oldMissionTemplate['objectif']));
            $message->line(new HtmlString($this->changes['objectif']));
        }

        $message
            ->action('Modérer le modèle de mission', $this->trackedUrl('/admin/contenus/modeles-mission/' . $this->missionTemplate->id . '/edit'))
            ->tag($this->tag);

        return $message;
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
