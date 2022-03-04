<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ReferentDailyTodo extends Notification
{
    use Queueable;

    public $missions;
    public $structures;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($missions, $structures)
    {
        $this->missions = $missions;
        $this->structures = $structures;
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
        $mailMessage = (new MailMessage)
            ->subject('Actions à faire - ça bouge dans votre département !')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line('Il y a du nouveau dans votre département !')
            ->line('Votre action est requise pour permettre la publication de nouveaux contenus :');
        if (count($this->structures) > 0) {
            if (count($this->structures) == 1) {
                $mailMessage->line('- ' . count($this->structures) . ' nouvelle organisation en attente de validation');
            } else {
                $mailMessage->line('- ' . count($this->structures) . ' nouvelles organisations en attente de validation');
            }
        }
        if (count($this->missions) > 0) {
            if (count($this->missions) == 1) {
                $mailMessage->line('- ' . count($this->missions) . ' nouvelle mission en attente de validation');
            } else {
                $mailMessage->line('- ' . count($this->missions) . ' nouvelles missions en attente de validation');
            }
        }
        $mailMessage->action('Gérer les contenus', url(config('app.front_url') . '/dashboard'));

        $mailMessage->line('Merci beaucoup par avance pour votre action.');

        return $mailMessage;
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
