<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // public function index()
    // {
    //     return QueryBuilder::for(User::class)
    //             ->allowedFilters('name', 'is_admin')
    //             ->defaultSort('-created_at')
    //             ->paginate(config('query-builder.results_per_page'));
    // }

    public function me(Request $request)
    {
        $user = User::with('profile', 'profile.media', 'profile.skills')->find(Auth::guard('api')->user()->id);
        $user->append(['roles']);
        $user->profile->append(['avatar', 'domaines']);

        return $user;
    }

    public function unreadMessages(Request $request)
    {
        ray("que donne le call ?");
        $user = User::find(Auth::guard('api')->user()->id);
        ray($user->getUnreadConversationsCount());

        return $user->getUnreadConversationsCount();
    }

    // public function structure(Request $request)
    // {
    //     $profile = Profile::where('user_id', Auth::guard('api')->user()->id)->first();

    //     return $profile->structures->first();
    // }

    // public function roles(Request $request)
    // {
    //     $user = User::where('id', Auth::guard('api')->user()->id)->first();

    //     return $user->roles;
    // }

    public function update(Request $request)
    {
        $user = $request->user();

        $messages = [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email,' . $user->id]
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update($request->all());

        $user->load('profile', 'profile.media', 'profile.skills');
        $user->append(['roles']);
        $user->profile->append(['avatar', 'domaines']);

        return $user;
    }

    public function impersonate(User $user)
    {
        $token = $user->createToken('impersonate');
        $token->token->expires_at = now()->addMinutes(60);
        $token->token->save();

        return $token;
    }

    public function stopImpersonate(Token $token)
    {
        return (string) $token->revoke();
    }

    public function anonymize(Request $request)
    {
        $user = $request->user();

        // Si je suis le dernier responsable d'une organisation on la désinscrit
        if ($user->profile->structures) {
            foreach ($user->profile->structures as $structure) {
                if ($structure->members->count() == 1) {
                    $structure->state = 'Désinscrite';
                    $structure->save();
                }
            }
        }

        $user->anonymize();

        return $user;
    }
}
