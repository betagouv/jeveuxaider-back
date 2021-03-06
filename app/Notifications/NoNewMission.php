<?php

namespace App\Notifications;

use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoNewMission extends Notification
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
            ->subject('Publiez une nouvelle mission sur JeVeuxAider.gouv.fr')
            ->greeting('Bonjour '.$notifiable->first_name.' 👋,')
            ->line('Cela fait quelques temps que vous n’avez pas proposé de mission sur JeVeuxAider.gouv.fr.')
            ->line('💡 Si vous souhaitez à nouveau recruter des bénévoles, vous pouvez publier une nouvelle mission en moins de 5 minutes.')
            ->action('Je propose une mission', url(config('app.front_url').'/admin/organisations/'.$this->structure->id.'/missions/add'))
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !');
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
