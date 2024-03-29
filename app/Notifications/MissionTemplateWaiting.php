<?php

namespace App\Notifications;

use App\Models\MissionTemplate;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionTemplateWaiting extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $missionTemplate;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(MissionTemplate $missionTemplate)
    {
        $this->missionTemplate = $missionTemplate;
        $this->tag = 'app-modele-mission-en-attente-de-validation';
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
        $message = (new MailMessage())
            ->subject($this->missionTemplate->reseau->name . ' : Nouveau modèle de mission en attente de validation')
            ->greeting('Bonjour,')
            ->line('Le réseau ' . $this->missionTemplate->reseau->name . ' a posté un nouveau modèle de mission : **' . $this->missionTemplate->title . '**')
            ->line('Passez le statut en "Validé" et cochez la case "En ligne" pour le rendre utilisable par les antennes du réseau sans qu\'il y ait besoin de validations futures par les référents.')
            ->action('Modérer le modèle de mission', $this->trackedUrl('/admin/contenus/modeles-mission?filter[state]=waiting'))
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
