<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;
use Illuminate\Support\HtmlString;

class StructureCreated extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Structure
     */
    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Structure $structure)
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
        return (new MailMessage)
            ->subject('Votre structure est bien inscrite')
            ->greeting('Bonjour ' . $notifiable->profile->full_name . ' !')
            ->line('Votre structure est bien enregistrée sur la plateforme de dépôt des missions de la phase 2 du SNU.')
            ->line('Vous pouvez désormais:')
            ->line(new HtmlString('<ul><li>Proposer une mission d’intérêt général</li><li>Inviter d’autres membres de l’équipe de votre structure à proposer une mission d’intérêt général.</li></ul>'))
            ->line('Pour toute question concernant l’éligibilité de votre structure ou les missions que vous souhaitez proposer, nous vous invitons à contacter votre service référent départemental.')
            ->line('Nous vous rappelons que les structures pouvant proposer une mission d’intérêt général pour le SNU sont les associations, les structures publiques, les entreprises agréées « Entreprise solidaire d’utilité sociale » (ESUS) et les établissements de santé privés d’intérêt collectif. Les associations soumises à la loi de 1905, les partis politiques ou les syndicats ne peuvent proposer de missions.')
            ->action('Accéder à mon compte', url(config('app.url')));
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
