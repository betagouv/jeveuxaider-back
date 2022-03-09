<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;
use Illuminate\Contracts\Queue\ShouldQueue;

class MissionBeingProcessed extends Notification implements ShouldQueue
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
        $message =  (new MailMessage)
            ->from("contact@reserve-civique.on.crisp.email", "JeVeuxAider.gouv.fr")
            ->subject('Votre mission est en cours de traitement')
            ->greeting('Bonjour ' . $notifiable->first_name . ',');
        $message->line("Votre mission « " . $this->mission->name . " » est actuellement en cours de traitement.");
        $message->line("En vue d’instruire votre proposition de mission, le référent départemental vous contactera très prochainement pour obtenir des informations complémentaires sur le format de mission envisagé.");
        $message->line("Pour toute question, vous pouvez contacter le Support Utilisateurs en répondant à ce mail.");

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
