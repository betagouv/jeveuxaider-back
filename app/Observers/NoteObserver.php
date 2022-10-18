<?php

namespace App\Observers;

use App\Models\Note;
use App\Models\Profile;
use App\Models\User;
use App\Notifications\NoteCreated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class NoteObserver
{
    /**
     * Handle the Note "created" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function created(Note $note)
    {

        if($note->user->is_admin){
            // Notify referent with tag Référent départemental - Contact principal
            if($note->notable->department) {
                $referents = Profile::where('referent_department', $note->notable->department)
                    ->whereHas('tags', function (Builder $query) {
                        $query->where('name', 'Référent départemental - Contact principal');
                    })
                    ->get();
                if($referents){
                    $referents->map(function($referent) use ($note) {
                        $referent->notify(new NoteCreated($note));
                    });
                }
            }
        } else {
            Notification::route('slack', config('services.slack.hook_url'))
                ->notify(new NoteCreated($note), 'slack');
        }
    }

    /**
     * Handle the Note "updated" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function updated(Note $note)
    {
        //
    }

    /**
     * Handle the Note "deleted" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function deleted(Note $note)
    {
        //
    }

    /**
     * Handle the Note "restored" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function restored(Note $note)
    {
        //
    }

    /**
     * Handle the Note "force deleted" event.
     *
     * @param  \App\Models\Note  $note
     * @return void
     */
    public function forceDeleted(Note $note)
    {
        //
    }
}
