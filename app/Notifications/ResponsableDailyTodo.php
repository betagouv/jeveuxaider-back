<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableDailyTodo extends Notification implements ShouldQueue
{
    use Queueable;

    public $participations;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($participations)
    {
        $this->participations = $participations;
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

        return (new MailMessage)
            ->when($participationsCount == 1, function (MailMessage $mailMessage) use ($notifiable) {
                return $mailMessage->subject("Vous avez une participation à traiter en priorité ! 🙌");
            }, function ($mailMessage) use ($notifiable, $participationsCount) {
                return $mailMessage->subject("Vous avez des participations à traiter en priorité ! 🙌");
            })
            ->markdown('emails.responsables.participations-rappel-waiting-validation', [
                'url' => url(config('app.front_url').'/admin/participations?filter%5Bstate%5D=En%20attente%20de%20validation'),
                'participationsCount' => $participationsCount,
                'notifiable' => $notifiable
            ])
            ->tag('app-responsable-rappel-participations-en-attente-de-validation');
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
