<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionInDraft extends Notification
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
        $label = $this->mission->template_id ? "Enregistrer et publier" : "Soumettre à validation";

        return (new MailMessage)
            ->from("contact@reserve-civique.on.crisp.email", "JeVeuxAider.gouv.fr")
            ->subject("Votre mission « " . $this->mission->name . " » est restée au statut « Brouillon »")
            ->greeting('Bonjour ' . $notifiable->first_name . ' 👋,')
            ->line("L'une de vos missions est encore au statut « Brouillon » : les visiteurs ne peuvent pas la consulter pour le moment. C'est dommage !")
            ->line("Pour la mettre en ligne, il suffit de modifier la mission concernée puis de cliquer sur le bouton « " . $label . " » en bas de page.")
            ->action('Je change le statut de la mission', url(config('app.url'). '/dashboard/mission/' . $this->mission->id . '/edit'))
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
