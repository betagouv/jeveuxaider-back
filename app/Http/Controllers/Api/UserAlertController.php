<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UserAlertCreateRequest;
use App\Http\Requests\Api\UserAlertUpdateRequest;
use App\Models\UserAlert;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class UserAlertController extends Controller
{
    public function index(Request $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;

        return QueryBuilder::for(UserAlert::ofUser($currentUserId))
            ->defaultSort('-created_at')
            ->paginate(config('query-builder.results_per_page'));
    }

    public function store(UserAlertCreateRequest $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $currentUser = User::find($currentUserId);

        if($currentUser->alerts->count() >= 3) {
            return abort('422', "Vous pouvez seulement créer 3 alertes.");
        }

        $alert = UserAlert::create([
            'user_id' => $currentUserId,
            'conditions' => [
                'type_missions' => $request->type_missions,
                'activities' => $request->activities,
                'address' => $request->address,
                'commitment' => $request->commitment,
            ],
        ]);

        return $alert;
    }

    public function update(UserAlertUpdateRequest $request)
    {
        $currentUserId = Auth::guard('api')->user()->id;
        $currentUser = User::find($currentUserId);

        if($currentUser->alerts->count() >= 3) {
            return abort('422', "Vous pouvez seulement créer 3 alertes.");
        }

        $alert = UserAlert::create([
            'user_id' => $currentUserId,
            'conditions' => [
                'type' => $request->type,
                'activities' => $request->activities,
                'address' => $request->address,
            ],
        ]);

        return $alert;
    }

    public function delete(Request $request, UserAlert $alert)
    {
        $currentUser = User::find(Auth::guard('api')->user()->id);

        if($alert->user_id != $currentUser->id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return (string) $alert->delete();
    }
}
