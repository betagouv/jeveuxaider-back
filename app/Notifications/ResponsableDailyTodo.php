<?php

namespace App\Notifications;

use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableDailyTodo extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    public $participations;
    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($participations)
    {
        $this->participations = $participations;
        $this->tag = 'app-responsable-rappel-participations-en-attente-de-validation';
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
        $participationsCount = count($this->participations);

        return (new MailMessage())
            ->when($participationsCount == 1, function (MailMessage $mailMessage) use ($notifiable) {
                return $mailMessage->subject("Vous avez une participation à traiter en priorité ! 🙌");
            }, function ($mailMessage) use ($notifiable, $participationsCount) {
                return $mailMessage->subject("Vous avez des participations à traiter en priorité ! 🙌");
            })
            ->markdown('emails.responsables.participations-rappel-waiting-validation', [
                'url' => $this->trackedUrl('/admin/participations?filter[state]=En attente de validation'),
                'participationsCount' => $participationsCount,
                'notifiable' => $notifiable
            ])
            ->tag($this->tag);
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
