<?php

namespace App\Observers;

use App\Jobs\SendinblueSyncUser;
use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
    }

    public function saving(User $user)
    {
        if($user->context_role != 'responsable') {
            $user->contextable_type = null;
            $user->contextable_id = null;
        }
    }
}
