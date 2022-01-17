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
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line(new HtmlString("Vous venez de rejoindre la plateforme <a href=" . url(config('app.url')) . ">JeVeuxAider.gouv.fr</a> proposée par la Réserve Civique : bienvenue ! Toute l'équipe est ravie de vous compter parmi les 1300 collectivités membres."))
            ->line(new HtmlString("Pour faire connaissance, nous vous invitons à prévoir avec nous un premier rdv ici 👉&nbsp;&nbsp; <a href='https://calendly.com/maiwelle-mezi'>https://calendly.com/maiwelle-mezi</a>."))
            ->line("Au programme :")
            ->line(new HtmlString("- 💻&nbsp;&nbsp; On vous présente la plateforme et son fonctionnement."))
            ->line(new HtmlString("- ❓&nbsp;&nbsp; On répond à vos questions."))
            ->line(new HtmlString("- 🔑&nbsp;&nbsp; On vous donne toutes les clés pour faire de votre expérience un succès !"))
            ->line(new HtmlString("D'ici là, on vous invite à lire notre feuille de route en cliquant ici 👉&nbsp;&nbsp; <br><a href='https://jeveuxaider.notion.site/JeVeuxAider-gouv-fr-pour-les-communes-86488dc20b56452e8be00b7ccc9934ce'>https://jeveuxaider.notion.site/JeVeuxAider-gouv-fr-pour-les-communes-86488dc20b56452e8be00b7ccc9934ce</a>"))
            ->line("Vous pourrez ensuite poster vos premières missions et vous familiariser avec la plateforme !")
            ->line(new HtmlString("<a href=" . url(config('app.url')) . ">JeVeuxAider.gouv.fr</a> a pour mission de faciliter vos recrutements de bénévoles et de faire grandir l'engagement en France. Merci pour votre confiance !"))
            ->salutation(new HtmlString("À très vite,<br><br>Maiwelle Mezi<br>🚀&nbsp;&nbsp; Chargée de déploiement au sein de jeveuxaider.gouv.fr"))
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
