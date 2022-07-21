<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogObserver
{
    public function saving(ActivityLog $activityLog)
    {
        $user = Auth::guard('api')->user();

        if ($activityLog->subject_type == 'App\Models\Participation') {
            $activityLog->subject->load('profile');
            $subject_type = $activityLog->subject->profile->full_name;
        } elseif ($activityLog->subject_type == 'App\Models\Profile') {
            $subject_type = $activityLog->subject->full_name;
        } else {
            $subject_type = $activityLog->subject->name ?? '';
        }

        $activityLog->data = [
            'subject_title' => $subject_type,
            'full_name' => $user && $user->profile ? $user->profile->full_name : '',
            'causer_id' => $user && $user->profile ? $user->profile->id : '',
            'context_role' => $user->context_role ?? 'volontaire',
        ];
    }
}
