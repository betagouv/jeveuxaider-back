<?php

namespace App\Notifications;

use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoNewMission extends Notification implements ShouldQueue
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
            ->subject('Vous nous manquez sur JeVeuxAider.gouv.fr !')
            ->greeting('Bonjour '.$notifiable->profile->first_name.' 👋,')
            ->line('Nous avons remarqué que vous n’avez pas proposé de nouvelle mission depuis quelques temps sur JeVeuxAider.gouv.fr. Si vous avez besoin de bénévoles, publier une nouvelle mission ne vous prendra que 5 minutes !')
            ->line('Nous avons à coeur de vous accompagner dans l’engagement de vos bénévoles sur l’ensemble de vos missions. ')
            ->action('Proposer une mission', url(config('app.front_url').'/admin/organisations/'.$this->structure->id.'/missions/add'))
            ->line('En cas de besoin, vous pouvez répondre à ce mail pour échanger directement avec le support utilisateurs !')
            ->tag('app-responsable-no-new-mission');
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
