<?php

namespace App\Notifications;

use App\Models\Profile;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class RegisterUserVolontaireCejAdviser extends Notification implements ShouldQueue
{
    use Queueable;

    public $profile;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
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

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject($this->profile->full_name . ' s’est inscrit sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line(new HtmlString($this->profile->full_name . ', que vous accompagnez dans le cadre du Contrat d’Engagement Jeune, a rejoint la plateforme <a href="https://www.jeveuxaider.gouv.fr/">JeVeuxAider.gouv.fr</a> en vue de réaliser une mission de bénévolat.'))
            ->line('Afin que vous puissiez l’accompagner dans son parcours en tant que bénévole, vous serez notifié par mail à son inscription sur une mission de bénévolat.')
            // @todo: livret conseiller & vidéo présentation JVA
            ->line('Pour toute question, vous pouvez contacter notre équipe par retour de mail.');
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
