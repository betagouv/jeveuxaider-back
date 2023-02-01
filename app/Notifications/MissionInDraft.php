<?php

namespace App\Notifications;

use App\Models\Mission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MissionInDraft extends Notification implements ShouldQueue
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
        $label = $this->mission->template_id ? 'Enregistrer et publier' : 'Soumettre à validation';

        return (new MailMessage)
            ->subject('Votre mission « '.$this->mission->name.' » est restée au statut « Brouillon »')
            ->greeting('Bonjour '.$notifiable->first_name.' 👋,')
            ->line("L'une de vos missions est encore au statut « Brouillon » : les visiteurs ne peuvent pas la consulter pour le moment. C'est dommage !")
            ->line('Pour la mettre en ligne, il suffit de modifier la mission concernée puis de cliquer sur le bouton « '.$label.' » en bas de page.')
            ->action('Je modifie la mission', url(config('app.front_url').'/admin/missions/'.$this->mission->id.'/edit'))
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !');
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
