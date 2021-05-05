<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class StructureCollectivityValidated extends Notification
{
    use Queueable;

    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($structure)
    {
        $this->structure = $structure;
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
        $mailMessage = (new MailMessage)
            ->subject('Bienvenue sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour ' . $notifiable->profile->first_name . ',')
            ->line("Vous venez de rejoindre la plateforme JeVeuxAider.gouv.fr proposée par la Réserve Civique : bienvenue ! Toute l’équipe est ravie de vous compter parmi les 5000 organisations membres.")
            ->line("Pour faire connaissance, je vous invite à notre session d'accueil. Au programme :")
            ->line("- 💻 On vous présente la plateforme et son fonctionnement.")
            ->line("- ❓ On répond à vos questions.")
            ->line("- 🔑 On vous donne toutes les clés pour faire de votre expérience un succès !")
            ->line(new HtmlString("Pour vous inscrire, c'est par ici 👉 : <a href='https://app.livestorm.co/jeveuxaider/session-decouverte-collectivites-territoriales?type=detailed'>https://app.livestorm.co/jeveuxaider/session-decouverte-collectivites-territoriales?type=detailed</a>"))
            ->line("D'ici là, vous pouvez déjà poster vos premières missions et vous familiariser avec la plateforme.")
            ->action('Créer une mission', url(config('app.url') . '/dashboard/structure/' . $this->structure->id . '/missions/add'))
            ->line("JeVeuxAider.gouv.fr a pour mission de faciliter vos recrutements de bénévoles et de faire grandir l’engagement en France. Merci pour votre confiance !")
        ;

        return $mailMessage;
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
