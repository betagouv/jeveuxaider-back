<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;

class StructureSignaled extends Notification
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
            ->subject('Votre organisation a été signalée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Votre organisation « ' . $this->structure->name . ' » ne répond pas aux exigences de la Charte de la Réserve Civique et/ou aux règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la réserve civique.')
            ->line('Par conséquent, votre organisation et vos éventuelles missions ont été également signalées et dépubliées de la plateforme. Si des volontaires étaient inscrits à l\'une de vos missions à venir, ils ont automatiquement été notifiés de leur annulation.')
        ;
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
