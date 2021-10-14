<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Mission;

class MissionValidated extends Notification
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

        $message =  (new MailMessage)
            ->subject('Votre mission est validée !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',');

        if ($this->mission->city && $this->mission->type == 'Mission en présentiel') {
            $message->line('Nous avons le plaisir de vous informer que la mission « ' . $this->mission->name. ' » proposée à ' . $this->mission->city . ' a bien été validée. Elle sera proposée aux bénévoles de JeVeuxAider.gouv.fr.');
        } else {
            $message->line('Nous avons le plaisir de vous informer que la mission « ' . $this->mission->name. ' » a bien été validée. Elle sera proposée aux bénévoles de JeVeuxAider.gouv.fr.');
        }

        $message->line("Dès qu'un bénévole candidatera à votre mission, nous vous transmettrons automatiquement ses coordonnées. Vous pourrez alors valider ou refuser sa candidature, et échanger directement avec lui sur la messagerie intégrée à la plateforme.")
            ->action('Accéder à mon compte', url(config('app.url') . '/dashboard/structure/' . $this->mission->structure->id . '/missions'));

        return $message;
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
