<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;

class ProfileInvitationSent extends Notification
{
    use Queueable;

    public $user;
    public $role;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $role)
    {
        $this->user = $user;
        $this->role = $role;
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
            ->greeting('Bonjour ' . $notifiable->first_name . ',');

        if ($this->role == 'referent') {
            $message
                ->line($this->user->profile->full_name . ' vous invite à devenir le référent du département ' . $notifiable->referent_department. ' sur la plateforme de dépôts de missions de JeVeuxAider.');
        }

        if ($this->role == 'referent_regional') {
            $message
                ->line($this->user->profile->full_name . ' vous invite à devenir le référent de la région ' . $notifiable->referent_region. ' sur la plateforme de dépôts de missions de JeVeuxAider.');
        }

        if ($this->role == 'superviseur') {
            $message
                ->line($this->user->profile->full_name . ' vous invite à devenir le superviseur du réseau ' . $notifiable->reseau->name. ' sur la plateforme de dépôts de missions de JeVeuxAider.');
        }

        if ($this->role == 'analyste') {
            $message
                ->line($this->user->profile->full_name . ' vous invite à accéder au tableau de bord de la plateforme de dépôts de missions de JeVeuxAider.');
        }

        if (!$notifiable->user) {
            $message
                ->line('Créez votre compte maintenant en utilisant votre adresse email pour accéder à votre espace et proposer vos missions.')
                ->action('Créer mon compte', url(config('app.url') . '/register/invitation?' . http_build_query([
                    'email' => $notifiable->email,
                    'first_name' => $notifiable->first_name,
                    'last_name' => $notifiable->last_name
                ])));
        } else {
            $message
                ->action('Accéder à mon compte', url(config('app.url')));
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
