<?php

namespace App\Observers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function saving(Activity $activity)
    {
        $user = Auth::guard('api')->user();

        $activity->data = [
            "subject_title" => $activity->subject->name ?? '',
            "full_name" => $user->profile->fullName ?? '',
            "causer_id" => $user->profile->id ?? '',
            "context_role" => $user->contextRole ?? 'volontaire'
        ];
    }
}
