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
            ->greeting('Bonjour ' . $notifiable->profile->first_name . ',')
            ->line('Votre structure est bien enregistrée au dispositif d’urgence Covid-19 de la Réserve Civique.')
            ->line('Vous pouvez désormais:')
            ->line(new HtmlString('<ul><li>Proposer une mission d’intérêt général</li><li>Inviter d’autres membres de l’équipe de votre structure à proposer une mission d’intérêt général.</li></ul>'))
            ->line('Pour toute question concernant l’éligibilité de votre structure ou les missions que vous souhaitez proposer, nous vous invitons à contacter votre service référent départemental.')
            ->action('Publier votre première mission', url(config('app.url').'/dashboard'));
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
