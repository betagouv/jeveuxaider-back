<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionSignaled extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Mission
     */
    public $mission;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Mission $mission)
    {
        $this->mission = $mission;
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
            ->subject('Votre mission a été signalée')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Votre mission « ' . $this->mission->name . ' » ne répond pas aux exigences de la Charte de la Réserve Civique et/ou aux règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la réserve civique.')
            ->line('Par conséquent, votre mission « ' . $this->mission->name . ' » a été signalée et dépubliée de la plateforme et les éventuels bénévoles qui y étaient inscrits ont été notifiés de son annulation.')
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
