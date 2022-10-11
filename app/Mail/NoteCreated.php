<?php

namespace App\Mail;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NoteCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $note;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject('Nouvelle note postée par ' . $this->note->user->profile->full_name)
            ->markdown('emails.notes.created')
            ->with([
                'url' => url(config('app.front_url') . $this->generateFrontUrl()),
                'notable' =>  $this->note->notable
            ]);
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
