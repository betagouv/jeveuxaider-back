<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportReady extends Notification implements ShouldQueue
{
    use Queueable;

    public $user;

    public $filePath;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $filePath)
    {
        $this->user = $user;
        $this->filePath = $filePath;
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
            ->subject('Votre export est prêt')
            ->greeting('Bonjour '.$notifiable->profile->first_name.',')
            ->line('Votre fichier d\'export est prêt à être téléchargé.')
            // ->line('Le lien expirera dans 12 heures.') TODO : when new S3
            ->action('Télécharger le fichier', $this->filePath);
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
