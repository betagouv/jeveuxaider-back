<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class RegisterUserVolontaireCej extends Notification implements ShouldQueue
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
            ->subject($notifiable->first_name.', avec le Contrat d’Engagement Jeune, réalisez des missions de bénévolat !')
            ->greeting('Bonjour '.$notifiable->first_name.',')
            ->line('Savez-vous que les missions de bénévolat peuvent être effectuées dans le cadre du Contrat d’Engagement Jeune ?')
            ->line('Pour vous accompagner dans vos premiers pas en tant que bénévole, nous avons sélectionné des missions qui pourront vous plaire :')
            ->line(new HtmlString('<ul class="list-none"><li>🏀 <a href="'.config('app.front_url').'/activites/accompagnement-aux-activites-sportives">Accompagnement aux activités sportives</a></li>'))
            ->line(new HtmlString('<li>🥫 <a href="'.config('app.front_url').'/activites/collecte-de-produits">Collecte de produits</a></li>'))
            ->line(new HtmlString('<li>📆 <a href="'.config('app.front_url').'/activites/evenementiel">Evénementiel</a></li>'))
            ->line(new HtmlString('<li>♻️ <a href="'.config('app.front_url').'/activites/ramassage-de-dechets">Ramassage de déchets</a></li>'))
            ->line(new HtmlString('<li>👨‍🚒 <a href="'.config('app.front_url').'/activites/secourisme-et-securite-civile">Secourisme</a></li></ul>'))
            ->line('Intéressé par d’autres types de missions ? Retrouvez des milliers de missions près de chez vous ou bien à distance 👇')
            ->action('Trouver votre mission', url(config('app.front_url').'/missions-benevolat'));
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
