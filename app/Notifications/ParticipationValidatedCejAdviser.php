<?php

namespace App\Notifications;

use App\Models\Participation;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ParticipationValidatedCejAdviser extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
        $this->tag = 'app-accompagnant-cej-inscription-mission-par-benevole';
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
        // @todo: l'url des documents ne doit pas changer quand on les met à jour.

        $message = (new MailMessage())
            ->subject($this->participation->profile->full_name . ' s’est inscrit sur une mission de bénévolat')
            ->greeting('Bonjour,')
            ->line(new HtmlString($this->participation->profile->full_name . ', que vous accompagnez dans le cadre du Contrat d’Engagement Jeune, s’est inscrit sur la mission de bénévolat : <a href="' . $this->trackedUrl($this->participation->mission->full_url) . '">' . $this->participation->mission->name . '</a>.'))
            ->line('En cas de besoin, vous pouvez consulter les ressources mises à votre disposition :')
            ->line(new HtmlString('<ul><li><a href="https://www.jeveuxaider.gouv.fr/engagement/wp-content/uploads/2023/07/GUIDE-CEJ-Mise-a-jour-Janvier-2023.pdf">👉 Le Guide</a></li>'))
            ->line(new HtmlString('<li><a href="https://www.jeveuxaider.gouv.fr/engagement/wp-content/uploads/2023/07/Presentation-CEJ-A4-Synthetique-Mise-a-jour-Juin2023.pdf">👉 La Fiche récapitulative</a></li>'))
            ->line(new HtmlString('<li><a href="https://vimeo.com/750348414">👉 La vidéo de présentation de JeVeuxAider.gouv.fr</a></li></ul>'))
            ->line('Pour toute question, vous pouvez également contacter notre équipe par retour de mail.')
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
