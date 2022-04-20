<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;
use Illuminate\Contracts\Queue\ShouldQueue;

class StructureInDraft extends Notification implements ShouldQueue
{
    use Queueable;

    public $structure;
    public $template;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure, $template)
    {
        $this->structure = $structure;
        $this->template = $template;
    }

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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

        if($this->template == 'j+1') {
            return (new MailMessage)
            ->subject("Finalisez votre inscription sur JeVeuxAider.gouv.fr")
            ->greeting('Bonjour ' . $notifiable->first_name . ' 👋,')
            ->line("Vous avez créé un compte sur JeVeuxAider.gouv.fr en vue de publier des missions.")
            ->line("Malheureusement, nous ne pouvons, pour le moment, apporter une issue favorable à votre inscription.")
            ->line("En effet, vous n’avez pas encore complété les informations relatives à l’activité de votre structure !")
            ->line("Nous vous invitons à vous connecter et à compléter les informations de votre organisation")
            ->action('Je finalise mon inscription', url(config('app.front_url'). '/admin/organisations/' . $this->structure->id . '/edit'))
            ->line("Si vous souhaitez que l’organisation soit supprimée, merci de nous l’indiquer par retour de mail.");
        }

        if($this->template == 'j+7') {
            return (new MailMessage)
            ->subject($notifiable->first_name .", finalisez votre inscription pour recruter des bénévoles")
            ->greeting('Bonjour,')
            ->line("Il ne vous reste qu’une petite étape avant de pouvoir recruter des bénévoles : il vous suffit de compléter les informations de votre structure.")
            ->action('Je renseigne les informations manquantes', url(config('app.front_url'). '/admin/organisations/' . $this->structure->id . '/edit'))
            ->line("Si vous souhaitez que l’organisation soit supprimée, merci de nous l’indiquer par retour de mail.");
        }

        if($this->template == 'j+15') {
            return (new MailMessage)
            ->subject($notifiable->first_name .", des bénévoles vous attendent")
            ->greeting('Bonjour,')
            ->line("C’est la dernière ligne droite avant de pouvoir recruter des bénévoles : complétez les informations manquantes pour être mis en relation avec ceux-ci.")
            ->action('Je renseigne les informations manquantes', url(config('app.front_url'). '/admin/organisations/' . $this->structure->id . '/edit'))
            ->line("Si vous souhaitez que l’organisation soit supprimée, merci de nous l’indiquer par retour de mail.");
        }
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
