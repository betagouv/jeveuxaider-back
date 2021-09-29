<?php

namespace App\Notifications;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InvitationSent extends Notification
{
    use Queueable;

    public $invitation;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
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
            ->greeting('Bonjour,');

        if ($this->invitation->role == 'responsable_organisation') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à rejoindre la structure ' . $this->invitation->invitable->name . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'responsable_territoire') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à rejoindre le territoire ' . $this->invitation->invitable->name . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'responsable_antenne') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à créer l\'antenne ' . $this->invitation->properties['antenne_name'] . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'responsable_reseau') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à superviser le réseau ' . $this->invitation->invitable->name . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'referent_departemental') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à devenir le référent du département ' . $this->invitation->properties['referent_departemental'] . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'referent_regional') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à devenir le référent de la région ' . $this->invitation->properties['referent_regional'] . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'superviseur') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à devenir le superviseur du réseau ' . $this->invitation->invitable->name . ' sur la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'datas_analyst') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à accéder au tableau de bord de la plateforme de dépôts de missions de la Réserve Civique.');
        }

        if ($this->invitation->role == 'benevole') {
            $message
                ->line($this->invitation->user->profile->full_name . ' vous invite à accéder à la plateforme JeVeuxAider.gouv.fr de la Réserve Civique.');
        }

        $message
            ->action('Voir l\'invitation', url(config('app.url') . '/invitation/' . $this->invitation->token));

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
