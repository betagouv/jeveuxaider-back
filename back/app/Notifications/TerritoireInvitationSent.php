<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Structure;
use App\Models\Territoire;
use App\Models\User;

class TerritoireInvitationSent extends Notification
{
    use Queueable;

    /**
     * The order instance.
     *
     * @var Territoire
     */
    public $territoire;
    public $user;
    public $role;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Territoire $territoire, User $user, $role)
    {
        $this->territoire = $territoire;
        $this->user = $user;
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
        $message = (new MailMessage)
            ->subject('Invitation en attente')
            ->greeting('Bonjour ' . $notifiable->first_name . ',')
            ->line($this->user->profile->full_name . ' vous invite à devenir ' . $this->role . ' de ' . $this->structure->name . ' sur la plateforme de dépôts de missions de JeVeuxAider.gouv.fr.');

        if (!$notifiable->user) {
            $message
                ->line('Vous pouvez créer votre compte maintenant en utilisant votre adresse email pour accéder à votre espace et proposer vos missions.')
                ->action('Créer un compte', url(config('app.front_url') . '/register/invitation?' . http_build_query([
                    'email' => $notifiable->email,
                    'first_name' => $notifiable->first_name,
                    'last_name' => $notifiable->last_name
                ])));
        } else {
            $message
                ->line('Vous pouvez, en utilisant votre adresse email, accéder à votre espace et proposer vos missions d’intérêt général.')
                ->action('Accéder à mon compte', url(config('app.front_url')));
        }

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
