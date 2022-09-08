<?php

namespace App\Notifications;

use App\Models\NotificationTemoignage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotificationTemoignageCreate extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var NotificationTemoignage
     */
    public $notificationTemoignage;

    /**
     * The order instance.
     *
     * @var Participation
     */
    public $participation;

    public $mission;

    public $structure;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotificationTemoignage $notificationTemoignage)
    {
        $this->notificationTemoignage = $notificationTemoignage;
        $this->participation = $this->notificationTemoignage->participation;
        $this->mission = $this->participation->mission;
        $this->structure = $this->mission->structure;
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
        return (new MailMessage)
            ->subject("Comment s'est déroulée votre mission ?")
            ->greeting('Bonjour ' . $notifiable->profile->first_name . ',')
            ->line('La mission « ' . $this->mission->name . ' » est désormais finie ! ' . $this->structure->name . " et toute l'équipe de JVA tenons à vous remercier pour votre engagement.")
            ->line('Prenez désormais le temps de nous raconter votre expérience 😉')
            ->action('Raconter mon expérience', url(config('app.front_url') . '/temoignages/' . $this->notificationTemoignage->token))
            ->line('À bientôt sur JeVeuxAider.gouv.fr !');
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
