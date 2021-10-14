<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionAlmostFull extends Notification
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
            ->subject("Votre mission « " . $this->mission->name . " » est bientôt complète !")
            ->greeting('Bonjour ' . $notifiable->first_name . ' 👋,')
            ->line("Félicitations, votre mission est bientôt complète ! Pour rappel, lorsque votre mission est complète, les bénévoles ne peuvent plus y candidater.")
            ->line("Si vous le souhaitez, vous pouvez en un clic augmenter le nombre de bénévoles recherchés.")
            ->action("J'augmente la jauge de la mission", url(config('app.url'). '/dashboard/mission/' . $this->mission->id . '/edit'))
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
