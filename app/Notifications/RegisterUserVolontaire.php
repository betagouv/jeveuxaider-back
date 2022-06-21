<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\HtmlString;

class RegisterUserVolontaire extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject('💪 Une dernière étape pour s’engager ' . $notifiable->profile->full_name . ' !')
            ->greeting('Félicitations ' . $notifiable->profile->full_name . ' !')
            ->line('Vous vous êtes inscrit sur JeVeuxAider.gouv.fr, la plateforme publique du bénévolat. Voici une sélection de missions réalisables à cotés de chez vous ou à distance :')
            ->line(new HtmlString('<ul><li><a href="' . config('app.front_url') . '/missions-benevolat?query=mentorat">Aidez un jeune en difficulté scolaire en devenant mentor 1h par semaine !</a></li>'))
            ->line(new HtmlString('<li><a href="' . config('app.front_url') . '/missions-benevolat?template_subtitle=Je%20maintiens%20le%20lien%20avec%20des%20personnes%20fragiles%20isolées%7CJe%20participe%20à%20la%20lutte%20contre%20l%27isolement%20des%20personnes%20âgées%20ou%20en%20situation%20de%20handicap">Luttez contre l’isolement et la solitude des des personnes fragiles !</a></li></ul>'))
            ->line('Intéressé par d’autres types de missions ? Retrouvez des milliers de missions près de chez vous ou à distance ici !')
            ->action('Trouver une mission', url(config('app.front_url').'/missions-benevolat'));
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
