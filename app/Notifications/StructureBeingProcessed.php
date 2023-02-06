<?php

namespace App\Notifications;

use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureBeingProcessed extends Notification implements ShouldQueue
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

    public function viaQueues()
    {
        return [
            'mail' => 'emails',
        ];
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
            ->subject('L’inscription de votre organisation est en cours de traitement')
            ->greeting('Bonjour '.$notifiable->first_name.',');
        $message->line('L’inscription de votre organisation « '.$this->structure->name.' » est actuellement en cours de traitement.');
        $message->line('En vue d’instruire votre demande d’inscription sur JeVeuxAider.gouv.fr, le référent départemental vous contactera très prochainement pour obtenir des informations complémentaires quant à votre structure.');
        $message->line('Pour toute question, vous pouvez contacter le support utilisateurs en répondant à ce mail.');

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
