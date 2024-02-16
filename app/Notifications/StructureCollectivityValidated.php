<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class StructureCollectivityValidated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $structure;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($structure)
    {
        $this->structure = $structure;
        $this->tag = 'app-responsable-collectivite-validee';
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
        $mailMessage = (new MailMessage())
            ->subject('Découvrez le fonctionnement de la plateforme JeVeuxAider.gouv.fr')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line(new HtmlString('Vous venez de rejoindre la plateforme <a href="' . $this->trackedUrl('') . '">JeVeuxAider.gouv.fr</a> proposée par la Réserve Civique : bienvenue ! Toute l\'équipe est ravie de vous compter parmi les 1650 collectivités membres.'))
            ->line('Pour faire connaissance, nous vous invitons à notre session d\'accueil. Au programme :')
            ->line(new HtmlString('- 💻&nbsp;&nbsp; On vous présente la plateforme et son fonctionnement.'))
            ->line(new HtmlString('- ❓&nbsp;&nbsp; On répond à vos questions.'))
            ->line(new HtmlString('- 🔑&nbsp;&nbsp; On vous donne toutes les clés pour faire de votre expérience un succès !'))
            ->line("Pour vous inscrire, c'est par ici 👉 : https://app.livestorm.co/jeveuxaider/session-daccueil-associations")
            ->line("Et d'ici là, on vous invite à lire notre feuille de route en cliquant ici 👉 : https://jeveuxaider.notion.site/JeVeuxAider-gouv-fr-pour-les-communes-86488dc20b56452e8be00b7ccc9934ce")
            ->line('Vous pourrez ensuite poster vos premières missions et vous familiariser avec la plateforme !')
            ->line(new HtmlString('<a href="' . $this->trackedUrl('') . '">JeVeuxAider.gouv.fr</a> a pour mission de faciliter vos recrutements de bénévoles et de faire grandir l\'engagement en France. Merci pour votre confiance !'))
            ->tag($this->tag);

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
