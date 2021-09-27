<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\MissionTemplate;
use Illuminate\Contracts\Queue\ShouldQueue;

class MissionTemplateCreated extends Notification implements ShouldQueue
{
    use Queueable;

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
    }

    public $missionTemplate;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionTemplate $missionTemplate)
    {
        $this->missionTemplate = $missionTemplate;
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
        $message =  (new MailMessage)
            ->subject($this->missionTemplate->reseau->name . ' : Nouveau modèle de mission en attente de validation')
            ->greeting('Bonjour,')
            ->line('Le réseau ' . $this->missionTemplate->reseau->name . ' a posté un nouveau modèle de mission : **' . $this->missionTemplate->title . '**')
            ->line('Cochez la case "En ligne" pour le rendre utilisable par les antennes du réseau sans qu\'il y est besoin de validation futures par les référents.')
            ->action('Modérer le modèle de mission', url(config('app.url') . '/dashboard/contents/template/' . $this->missionTemplate->id . '/edit'));

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
