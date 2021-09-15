<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\NotificationTemoignage;
use App\Models\Participation;
use App\Models\Temoignage;
use App\Notifications\NotificationTemoignageCreate;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;

class NotificationTemoignageController extends Controller
{
    public function show(Request $request, String $token)
    {
        return NotificationTemoignage::whereToken($token)->first();
    }

    public function fromParticipation(Request $request, Participation $participation)
    {
        return $participation->notificationTemoignage;
    }

    public function resend(Request $request, NotificationTemoignage $notificationTemoignage)
    {
        // Seulement si temoignage non existant.
        $temoignagesCount = Temoignage::where('participation_id', $notificationTemoignage->participation_id)->count();
        if ($temoignagesCount > 0) {
            abort(403, "Un témoignage existe déjà pour cette participation !");
        }

        $notificationTemoignage->reminders_sent++;
        $notificationTemoignage->last_sent_at = $notificationTemoignage->freshTimestamp();
        $notificationTemoignage->save();

        $notificationTemoignage->participation->profile->user->notify(new NotificationTemoignageCreate($notificationTemoignage));

        return $notificationTemoignage;
    }
}
