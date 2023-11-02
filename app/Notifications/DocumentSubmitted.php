<?php

namespace App\Notifications;

use App\Models\Document;
use App\Traits\TransactionalEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DocumentSubmitted extends Notification implements ShouldQueue
{
    use Queueable;
    use TransactionalEmail;

    /**
     * The order instance.
     *
     * @var Document
     */
    public $document;

    public $tag;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
        $this->tag = 'app-document-submitted';
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
        return ['mail', 'database'];
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
            ->subject('Une nouvelle ressource est accessible dans votre espace')
            ->markdown('emails.referents.document-submitted', [
                'url' => $this->trackedUrl('/admin/ressources'),
                'document' => $this->document,
                'notifiable' => $notifiable,
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
            'ressource_id' => $this->document->id,
            'ressource_title' => $this->document->title,
        ];
    }
}
