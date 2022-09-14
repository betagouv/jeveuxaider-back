<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationBenevoleRequest;
use App\Models\NotificationBenevole;
use App\Models\Profile;
use App\Notifications\NotificationToBenevole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationBenevoleController extends Controller
{
    public function index(Request $request)
    {
        return QueryBuilder::for(NotificationBenevole::role($request->header('Context-Role')))
            ->allowedFilters(
                AllowedFilter::exact('profile.id'),
                AllowedFilter::exact('mission.id')
            )
            ->defaultSort('-created_at')
            ->paginate(1000);
    }

    public function store(NotificationBenevoleRequest $request)
    {
        $profile_id = request('profile_id');

        $notificationBenevoleCount = NotificationBenevole::where(
            'profile_id',
            $profile_id
        )->where('mission_id', request('mission_id'))->count();
        if ($notificationBenevoleCount > 0) {
            abort(422, 'Vous avez déjà envoyé un e-mail à ce bénévole');
        }

        $notificationBenevoleStats = Profile::getNotificationBenevoleStats($profile_id);
        if ($notificationBenevoleStats['thisMonth'] >= 2) {
            abort(422, 'Le bénévole à déjà été sollicité 2 fois ce mois-ci');
        }

        $notificationBenevole = NotificationBenevole::create(
            array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id])
        );

        Profile::find(request('profile_id'))->notify(new NotificationToBenevole($notificationBenevole));

        return $notificationBenevole;
    }
}
