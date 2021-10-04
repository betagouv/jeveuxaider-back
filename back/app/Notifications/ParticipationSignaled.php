<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Participation;

class ParticipationSignaled extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Participation $participation)
    {
        $this->participation = $participation;
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
            ->line('Votre mission « ' . $this->participation->mission->name . ' » à laquelle vous vous êtes inscrit.e a été annulée car l\'organisation ' . $this->participation->mission->structure->name. ' ne répond pas aux exigences de la Charte de la Réserve Civique et/ou aux règles fixés par le Décret n° 2017-930 du 9 mai 2017 relatif à la réserve civique.')
            ->line('Par conséquent, votre participation a automatiquement été annulée et anonymisée. L\'organisation n\'a donc plus accès à votre identité ou à vos coordonnées sur la plateforme.')
            ->line('Rendez vous dès à présent sur JeVeuxAider.gouv.fr pour découvrir les nouvelles missions en ligne !')
            ->action('Toutes nos missions', url(config('app.url') . '/missions'))
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
