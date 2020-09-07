<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;

class StructureWaitingValidation extends Notification
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
            ->subject('Votre organisation est en cours de validation')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Avant tout, merci d\'avoir rejoint la Réserve Civique !')
            ->line('Nous avons bien reçu la demande d\'inscription de votre organisation « ' . $this->structure->name . ' » et vous répondront sous peu quant à votre éligibilité.')
            ->line('Vous pouvez dès maintenant proposer une ou plusieurs missions. Elles seront publiées dès la validation de votre organisation par le référent de le Réserve Civique de votre département.')
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
