<?php

namespace App\Notifications;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NoteCreated extends Notification
{
    /**
     * The order instance.
     *
     * @var Note
     */
    public $note;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
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
            ->subject('Nouvelle note postée par ' . $this->note->user->profile->full_name)
            ->markdown('emails.notes.created', [
                'url' => url(config('app.front_url') . $this->generateFrontUrl()),
                'note' => $this->note,
                'notable' =>  $this->note->notable,
                'notifiable' => $notifiable
            ]);
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

    protected function generateFrontUrl()
    {
        switch($this->note->notable_type){
            case 'App\Models\Structure':
                return '/admin/organisations/' . $this->note->notable_id . '#notes';
            case 'App\Models\Mission':
                return '/admin/missions/' . $this->note->notable_id . '#notes';
        }
    }
}
