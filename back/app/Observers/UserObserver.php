<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
    }
}
