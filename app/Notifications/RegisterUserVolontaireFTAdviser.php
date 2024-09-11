<?php

namespace App\Notifications;

use App\Models\Profile;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class RegisterUserVolontaireFTAdviser extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $profile;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
        $this->tag = 'app-responsable-ft-inscription-benevole';
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

        return (new MailMessage())
            ->subject($this->profile->full_name . ' s’est inscrit sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour,')
            ->line(new HtmlString($this->profile->full_name . ', que vous accompagnez dans le cadre du RSA, a rejoint la plateforme <a href="' . $this->trackedUrl('') . '">JeVeuxAider.gouv.fr</a>.'))
            ->line(new HtmlString('<a href="' . $this->trackedUrl('') . '">JeVeuxAider.gouv.fr</a> est la <a href="' . $this->trackedUrl('') . '">plateforme publique du bénévolat</a>, proposée par la Réserve civique. Elle met en relation celles et ceux qui veulent agir pour l’intérêt général avec les associations, établissements publics et communes qui ont besoin de bénévoles.'))
            ->line('Afin que vous puissiez l’accompagner dans son parcours en tant que bénévole, vous serez notifié par mail à son inscription sur une mission de bénévolat.')
            ->line('En cas de besoin, vous pouvez consulter les ressources mises à votre disposition :')
            ->line(new HtmlString('<ul><li><a href="https://www.jeveuxaider.gouv.fr/engagement/wp-content/uploads/2023/07/GUIDE-CEJ-Mise-a-jour-Janvier-2023.pdf">👉 Le Guide</a></li>'))
            ->line(new HtmlString('<li><a href="https://www.jeveuxaider.gouv.fr/engagement/wp-content/uploads/2023/07/Presentation-CEJ-A4-Synthetique-Mise-a-jour-Juin2023.pdf">👉 La Fiche récapitulative</a></li>'))
            ->line(new HtmlString('<li><a href="https://vimeo.com/750348414">👉 La vidéo de présentation de JeVeuxAider.gouv.fr</a></li></ul>'))
            ->line('Pour toute question, vous pouvez également contacter notre équipe par retour de mail.')
            ->tag($this->tag);
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
