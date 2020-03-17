<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionWaitingCorrection extends Notification
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
        return (new MailMessage)
            ->subject('Vous devez modifier votre mission')
            ->greeting('Bonjour ' . $notifiable->full_name . ' !')
            ->line('Vous avez déposé une proposition de mission d’intérêt général pour la phase 2 du SNU et nous vous en remercions.')
            ->line('Néanmoins, nous n’avons pu valider votre mission.')
            ->line('Nous vous invitons à modifier en conséquence votre mission et la proposer à nouveau pour validation.')
            ->action('Modifier la mission', url(config('app.url') . '/mission/' . $this->mission->id . '/edit'))
            ->line('L’équipe en charge du SNU dans votre département se tient à votre disposition pour tout renseignement complémentaire,');
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
