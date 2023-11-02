<?php

namespace App\Notifications;

use App\Models\Structure;
use App\Models\User;
use App\Traits\TransactionalEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class StructureAskUnregister extends Notification
{
    use TransactionalEmail;

    public $structure;
    public $user;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, Structure $structure)
    {
        $this->user = $user;
        $this->structure = $structure;
        $this->tag = 'app-organisation-demande-desinscription';
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
        return (new MailMessage())
            ->subject($this->structure->name . ' souhaite se désinscrire...')
            ->markdown('emails.structures.ask-unregister', [
                'url' => $this->trackedUrl('/admin/organisations/' . $this->structure->id),
                'structure' => $this->structure,
                'user' => $this->user,
            ])
            ->tag($this->tag);
    }

    public function toSlack($notifiable)
    {
        $structure = $this->structure;
        $from = config('app.env') != 'production' ? '[' . config('app.env') . '] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';
        $url = url(config('app.front_url') . '/admin/organisations/' . $structure->id);

        return (new SlackMessage())
            ->from($from)
            ->success()
            ->to('#' . config('services.slack.log_channel'))
            ->content('*' . $this->user->profile->full_name . '* souhaite désinscrire l\'organisation *<' . $url . '|' . $structure->name . '>*')
            ->attachment(function ($attachment) use ($structure, $url) {
                $attachment
                    ->color('#BBBBBB')
                    ->fields([
                        'Nombre de participations' => $structure->missions()->count(),
                        'Nombre de missions' => $structure->missions()->count(),
                        'Statut' => $structure->state
                    ]);
            });
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
