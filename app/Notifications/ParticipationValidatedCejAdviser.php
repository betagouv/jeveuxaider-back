<?php

namespace App\Notifications;

use App\Models\Participation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ParticipationValidatedCejAdviser extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
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
        $message = (new MailMessage)
            ->subject($this->participation->profile->full_name.' s’est inscrit sur une mission de bénévolat')
            ->greeting('Bonjour,')
            ->line(new HtmlString($this->participation->profile->full_name.', que vous accompagnez dans le cadre du Contrat d’Engagement Jeune, s’est inscrit sur la mission <a href="'.url(config('app.front_url').$this->participation->mission->full_url).'">'.$this->participation->mission->name.'</a>.'))
            // @todo: livret conseiller & vidéo présentation JVA
            // ->line('En cas de besoin, vous pouvez consulter les ressources mises à votre disposition :')
            // ->line(new HtmlString('<ul><li><a href="">👉 Le Guide</a></li>'))
            // ->line(new HtmlString('<ul><li><a href="">👉 La Fiche A4</a></li>'))
            // ->line(new HtmlString('<ul><li><a href="">👉 La vidéo de présentation de JeVeuxAider.gouv.fr</a></li></ul>'))
            ->line('Pour toute question, vous pouvez également contacter notre équipe par retour de mail.');

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
