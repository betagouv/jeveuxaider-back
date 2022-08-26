<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BenevoleCejNoParticipation extends Notification implements ShouldQueue
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
            ->subject($notifiable->first_name.', votre première mission de bénévolat vous attend sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line('Et si c’était le moment de vous engager sur une mission de bénévolat ?')
            ->line('Pour vous accompagner dans le choix de votre première mission, voici une liste d’activités que nous avons sélectionnées pour vous :')
            ->line(new HtmlString('<ul><li><a href="'.config('app.front_url').'/activites/accompagnement-aux-activites-sportives">🏀 Accompagnement aux activités sportives</a></li>'))
            ->line(new HtmlString('<li><a href="'.config('app.front_url').'/activites/collecte-de-produits">🥫 Collecte de produits</a></li>'))
            ->line(new HtmlString('<li><a href="'.config('app.front_url').'/activites/evenementiel">📆 Evénementiel</a></li>'))
            ->line(new HtmlString('<li><a href="'.config('app.front_url').'/activites/ramassage-de-dechets">♻️ Ramassage de déchets</a></li>'))
            ->line(new HtmlString('<li><a href="'.config('app.front_url').'/activites/secourisme-et-securite-civile">👨‍🚒 Secourisme</a></li></ul>'))
            ->line('Intéressé par d’autres types de missions ? Retrouvez des milliers de missions près de chez vous ou bien à distance 👇')
            ->action('Je trouve une mission', url(config('app.front_url').'/missions-benevolat'));
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
