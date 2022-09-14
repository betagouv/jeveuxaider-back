<?php

namespace App\Notifications;

use App\Models\MissionTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MissionTemplateUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    public $missionTemplate;

    public $oldMissionTemplate;

    public $changes;

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
        $message = (new MailMessage)
            ->subject($this->missionTemplate->reseau->name.' : Un modèle de mission a été modifié après validation')
            ->greeting('Bonjour,')
            ->line('Le réseau **'.$this->missionTemplate->reseau->name.'** a modifié son modèle de mission : **'.$this->missionTemplate->title.'**');

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

        $message->action('Modérer le modèle de mission', url(config('app.front_url').'/admin/contenus/modeles-mission/'.$this->missionTemplate->id.'/edit'));

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
