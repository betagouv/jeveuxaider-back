<?php

namespace App\Notifications;

use App\Models\Structure;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StructureWithoutMissionSecondReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public $structure;

    public $template;

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
        return (new MailMessage)
            ->subject('Les bénévoles n’attendent plus que vous !')
            ->markdown('emails.responsables.structure-without-mission-second-reminder', [
                'url' => url(config('app.front_url').'/admin/organisations/'.$this->structure->id.'/missions/add'),
                'structure' => $this->structure,
                'notifiable' => $notifiable
            ])
            ->tag('app-responsable-organisation-sans-mission-2');
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
