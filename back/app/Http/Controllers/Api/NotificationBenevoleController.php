<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NotificationBenevoleRequest;
use App\Models\NotificationBenevole;
use App\Models\Profile;
use App\Notifications\NotificationToBenevole;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;

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
            ->paginate(1000)
        ;
    }

    public function store(NotificationBenevoleRequest $request)
    {
        $NotificationBenevoleCount = NotificationBenevole::where('profile_id', request("profile_id"))->where('mission_id', request("mission_id"))->count();

        if ($NotificationBenevoleCount > 0) {
            abort(402, "Vous avez déjà envoyé un e-mail à ce bénévole !");
        }

        $notificationBenevole = NotificationBenevole::create(
            array_merge($request->validated(), ['user_id' => Auth::guard('api')->user()->id])
        );

        Profile::find(request('profile_id'))->notify(new NotificationToBenevole($notificationBenevole));

        return $notificationBenevole;
    }
}
