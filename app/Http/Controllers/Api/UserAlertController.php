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

        $userAlert = UserAlert::create([
            'user_id' => $currentUserId,
            'conditions' => $request->validated()
        ]);

        return $userAlert;
    }

    public function update(UserAlertUpdateRequest $request, UserAlert $userAlert)
    {
        $userAlert->update([
            'conditions' => $request->validated()
        ]);

        return $userAlert;
    }

    public function activate(Request $request, UserAlert $userAlert)
    {
        $this->authorize('update', $userAlert);

        $userAlert->activate();

        return $userAlert;
    }

    public function deactivate(Request $request, UserAlert $userAlert)
    {
        $this->authorize('update', $userAlert);

        $userAlert->deactivate();

        return $userAlert;
    }

    public function delete(Request $request, UserAlert $userAlert)
    {
        $this->authorize('delete', $userAlert);

        return (string) $userAlert->delete();
    }
}
