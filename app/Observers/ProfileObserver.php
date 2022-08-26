<?php

namespace App\Observers;

use App\Helpers\Utils;
use App\Jobs\SendinblueSyncUser;
use App\Models\Profile;
use App\Notifications\RegisterUserVolontaireCej;
use App\Notifications\RegisterUserVolontaireCejAdviser;
use Illuminate\Support\Facades\Notification;

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
        if (config('services.sendinblue.sync')) {
            if ($profile->user) {
                SendinblueSyncUser::dispatch($profile->user);
            }
        }
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

        if ($profile->isDirty(['referent_region', 'referent_department', 'is_analyste', 'tete_de_reseau_id'])) {
            $profile->user->resetContextRole();
        }

        if (config('services.sendinblue.sync')) {
            if ($profile->user) {
                SendinblueSyncUser::dispatch($profile->user);
            }
        }

        if ($profile->cej && $profile->getOriginal('cej') === false) {
            $profile->user->notify(new RegisterUserVolontaireCej($profile->user));
        }

        if (! empty($profile->cej_email_adviser) && $profile->getOriginal('cej_email_adviser') != $profile->cej_email_adviser) {
            Notification::route('mail', $profile->cej_email_adviser)->notify(new RegisterUserVolontaireCejAdviser($profile));
        }
    }

    /**
     * Listen to the Profile saving event.
     *
     * @param  \App\Models\Profile  $profile
     * @return void
     */
    public function saving(Profile $profile)
    {
        if ($profile->zip) {
            $profile->department = Utils::getDepartmentFromZip($profile->zip);
        }

        if ($profile->commitment__duration) {
            $profile->setCommitmentTotal();
        }
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
