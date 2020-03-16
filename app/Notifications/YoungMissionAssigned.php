<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Young;

class YoungMissionAssigned extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Young
     */
    public $young;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Young $young)
    {
        $this->young = $young;
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
            ->subject('Affectation et mise en relation')
            ->greeting('Bonjour ' . $notifiable->full_name . ' !')
            ->line($this->young->full_name . ', volontaire réalisant son SNU, a été affecté sur la mission ' . $this->young->mission->name. '.')
            ->line('Nous vous invitons à prendre contact avec ' . $this->young->first_name . ' (' . $this->young->email. ' / ' . $this->young->phone . ') pour le lancement effectif de sa mission auprès de votre structure.')
            ->line('Dès que le jeune aura commencé à effectuer sa mission, nous vous invitons à valider ce début de mission en passant son statut en « Mission en cours ».')
            ->line('Si ' . $this->young->first_name . ' ne vous donne pas de nouvelles, ou s’il/elle abandonne en cours de route sa mission, nous vous remercions de nous en informer en passant son statut à « Abandon de la mission ».')
            ->line('Nous espérons que cette mission d’intérêt général permettra de découvrir concrètement l’engagement en faveur des autres. L’équipe en charge du SNU dans votre département est à votre écoute pour tout renseignement ou questions que vous pourriez vous poser pendant la réalisation de cette mission.')
            ->action('Accéder au dossier du jeune', url(config('app.front_app_url') . '/young/' . $this->young->id));
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
