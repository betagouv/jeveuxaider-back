<?php

namespace App\Notifications;

use App\Models\Structure;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class StructureAskUnregister extends Notification
{
    public $structure;
    public $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Structure $structure)
    {
        $this->user = $user;
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
        return ['mail', 'slack'];
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
            ->subject($this->structure->name . ' souhaite se désinscrire...')
            ->markdown('emails.structures.ask-unregister', [
                'url' => url(config('app.front_url') . '/admin/organisations/' . $this->structure->id),
                'structure' => $this->structure,
                'user' => $this->user,
            ]);
    }

    public function toSlack($notifiable)
    {
        $structure = $this->structure;
        $from = config('app.env') != 'production' ? '['.config('app.env').'] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';
        $url = url(config('app.front_url') . '/admin/organisations/' . $structure->id);

        return (new SlackMessage)
            ->from($from)
            ->success()
            ->to('#produit-logs')
            ->content('*'.$this->user->profile->full_name . '* souhaite désinscrire l\'organisation *<'.$url.'|'.$structure->name.'>*');
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
