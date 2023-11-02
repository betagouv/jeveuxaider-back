<?php

namespace App\Notifications;

use App\Models\Note;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NoteCreated extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Note
     */
    public $note;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
        $this->tag = 'app-note-creee';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return isset($notifiable->id) ? ['mail'] : ['slack'];
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
        return (new MailMessage())
            ->subject('Nouvelle note postée par ' . $this->note->user->profile->full_name)
            ->markdown('emails.notes.created', [
                'url' => $this->trackedUrl($this->generateFrontUrl()),
                'note' => $this->note,
                'notable' =>  $this->note->notable,
                'notifiable' => $notifiable
            ])
            ->tag($this->tag);
    }

    public function toSlack($notifiable)
    {
        $note = $this->note;
        $notable = $this->note->notable;
        $from = config('app.env') != 'production' ? '[' . config('app.env') . '] JeVeuxAider.gouv.fr' : 'JeVeuxAider.gouv.fr';
        $url = url(config('app.front_url') . $this->generateFrontUrl());

        $content = '*' . $note->user->profile->full_name . '* a ajoutée une note à *<' . $url . '|' . $notable->name . '>*';
        if ($note->context) {
            $content .= "\n*Contexte:* " . $note->context;
        }

        return (new SlackMessage())
            ->from($from)
            ->success()
            ->to('#' . config('services.slack.log_channel'))
            ->content($content)
            ->attachment(function ($attachment) use ($note) {
                $attachment
                    ->color('#BBBBBB')
                    ->content($note->content);
            });
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
        switch($this->note->notable_type) {
            case 'App\Models\Structure':
                return '/admin/organisations/' . $this->note->notable_id . '#notes';
            case 'App\Models\Mission':
                return '/admin/missions/' . $this->note->notable_id . '#notes';
        }
    }
}
