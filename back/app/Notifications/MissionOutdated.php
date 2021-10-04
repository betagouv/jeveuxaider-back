<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionOutdated extends Notification
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
            ->from("contact@reserve-civique.on.crisp.email", "JeVeuxAider.gouv.fr")
            ->subject("Votre mission « " . $this->mission->name . " » a-t-elle pris fin ?")
            ->greeting('Bonjour ' . $notifiable->first_name . ' 👋,')
            ->line("L'une de vos missions est arrivée à échéance : la date de fin que vous avez renseignée est dépassée. Deux solutions s'offrent à vous :")
            ->line("- Si votre mission se poursuit, il suffit de mettre à jour la date de fin")
            ->line("- Si votre mission a pris fin, il faut la passer au statut « Terminé ».")
            ->action('Je mets à jour ma mission', url(config('app.url'). '/dashboard/structure/' . $this->mission->structure->id . '/missions?filter[id]=' . $this->mission->id))
            ->line("En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !");
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
