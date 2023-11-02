<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResponsableParticipationAModeredEnPriorite extends Notification implements ShouldQueue
{
    use Queueable;

    public $totalCount;
    public $waitingCount;
    public $inProgressCount;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($totalCount, $waitingCount, $inProgressCount)
    {
        $this->totalCount = $totalCount;
        $this->waitingCount = $waitingCount;
        $this->inProgressCount = $inProgressCount;
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

        return (new MailMessage)
            ->when($this->totalCount == 1, function (MailMessage $mailMessage) use ($notifiable) {
                return $mailMessage->subject("Vous avez une participation à traiter en priorité ! 🙌");
            }, function ($mailMessage) use ($notifiable) {
                return $mailMessage->subject("Vous avez " . $this->totalCount . " participations à traiter en priorité ! 🙌");
            })
            ->markdown('emails.responsables.participations-rappel-a-traiter-en-priorite', [
                'url' => url(config('app.front_url').'/admin/participations?filter%5Bneed_to_be_treated%5D=true'),
                'totalCount' =>  $this->totalCount,
                'waitingCount' =>  $this->waitingCount,
                'inProgressCount' =>  $this->inProgressCount,
                'notifiable' => $notifiable
            ])
            ->tag('app-responsable-rappel-participations-a-traiter-en-priorite');
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
