<?php

namespace App\Observers;

use App\Jobs\AirtableSyncObject;
use App\Jobs\SendinblueSyncUser;
use App\Models\Profile;
use App\Notifications\RegisterUserVolontaireCejAdviser;
use App\Notifications\RegisterUserVolontaireFTAdviser;
use Carbon\Carbon;
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

        if (config('services.sendinblue.sync')) {
            if ($profile->user) {
                if ($oldEmail != $newEmail) {
                    SendinblueSyncUser::dispatch($profile->user, $oldEmail);
                } else {
                    SendinblueSyncUser::dispatch($profile->user);
                }
            }
        }

        if (!empty($profile->cej_email_adviser) && $profile->getOriginal('cej_email_adviser') != $profile->cej_email_adviser) {
            Notification::route('mail', $profile->cej_email_adviser)->notify(new RegisterUserVolontaireCejAdviser($profile));
        }

        if (!empty($profile->ft_email_adviser) && $profile->getOriginal('ft_email_adviser') != $profile->ft_email_adviser) {
            Notification::route('mail', $profile->ft_email_adviser)->notify(new RegisterUserVolontaireFTAdviser($profile));
        }

        // Sync Airtable
        if (config('services.airtable.sync')) {
            if ($profile->user->hasRole(['referent', 'referent_regional'])) {
                AirtableSyncObject::dispatch($profile->user);
            }
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
        if ($profile->commitment__duration) {
            $profile->setCommitmentTotal();
        }

        if ($profile->cej !== $profile->getOriginal('cej')) {
            $profile->cej_updated_at = Carbon::now();
        }

        if ($profile->ft !== $profile->getOriginal('ft')) {
            $profile->ft_updated_at = Carbon::now();
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
