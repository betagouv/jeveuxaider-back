<?php

namespace App\Observers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class ActivityObserver
{
    public function saving(Activity $activity)
    {
        $user = Auth::guard('api')->user();

        if ($activity->subject_type == 'App\Models\Participation') {
            $activity->subject->load('profile');
            $subject_type = $activity->subject->profile->full_name;
        } elseif ($activity->subject_type == 'App\Models\Profile') {
            $subject_type = $activity->subject->full_name;
        } else {
            $subject_type = $activity->subject->name ?? '';
        }

        $activity->data = [
            "subject_title" => $subject_type,
            "full_name" => $user && $user->profile ? $user->profile->full_name : '',
            "causer_id" => $user && $user->profile ? $user->profile->id : '',
            "context_role" => $user->contextRole ?? 'volontaire'
        ];
    }
}
