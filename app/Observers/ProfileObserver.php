<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\Profile;

class ProfileObserver
{
    /**
     * Listen to the Profile created event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function created(Profile $profile)
    {
    }

    /**
     * Listen to the Profile updated event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function updated(Profile $profile)
    {
        $oldEmail = $profile->getOriginal('email');
        $newEmail = $profile->email;

        if ($oldEmail != $newEmail) {
            $profile->user()->update(['email' => $newEmail]);
        }

        SendinblueSyncUser::dispatch($profile->user);
    }

    /**
     * Listen to the Profile deleting event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function deleting(Profile $profile)
    {
        //
    }
}
