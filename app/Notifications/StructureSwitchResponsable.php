<?php

namespace App\Notifications;

use App\Models\Mission;
use App\Models\Profile;
use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureSwitchResponsable extends Notification implements ShouldQueue
{
    use Queueable;

    public $oldResponsable;
    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure, Profile $oldResponsable)
    {
        $this->structure = $structure;
        $this->oldResponsable = $oldResponsable;
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

        $message = (new MailMessage)
            ->subject($this->oldResponsable->full_name . ' vous a confié la gestion de nouvelles missions')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line($this->oldResponsable->full_name . ", qui assure également la gestion de l’organisation " . $this->structure->name . " sur JeVeuxAider.gouv.fr, s’est désinscrit de notre plateforme.")
            ->line("Dans ce cadre, il a indiqué que vous étiez le nouveau responsable des missions dont il assurait la gestion.")
            ->line("Vous serez automatiquement notifié des nouvelles propositions de participation sur les missions concernées.")
            ->line("Si vous souhaitez désigner un autre responsable de mission, il vous suffit d’éditer les missions concernées et désigner le responsable souhaité.")
            ->action('Accéder à mes missions', url(config('app.front_url') . '/admin/missions'));

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
            'structure_id' => $this->structure->id,
            'structure_name' => $this->structure->name,
            'new_responsable_first_name' => $notifiable->first_name,
            'new_responsable_last_name' => $notifiable->last_name,
            'old_responsable_first_name' => $this->oldResponsable->first_name,
            'old_responsable_last_name' => $this->oldResponsable->last_name,
        ];
    }
}
