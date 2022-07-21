<?php

namespace App\Http\Controllers\Api;

use App\Filters\FiltersParticipationSearch;
use App\Http\Controllers\Controller;
use App\Models\Participation;
use App\Models\User;
use App\Notifications\UserAnonymize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = User::with('profile', 'profile.avatar', 'profile.skills', 'profile.domaines', 'profile.reseau', 'profile.activities')->find(Auth::guard('api')->user()->id);
        $user->append(['roles', 'statistics']);

        return $user;
    }

    public function participations(Request $request)
    {
        $user = User::with(['profile'])->find(Auth::guard('api')->user()->id);

        return QueryBuilder::for(Participation::where('profile_id', $user->profile->id)->with('profile', 'mission'))
        ->allowedFilters(
            AllowedFilter::custom('search', new FiltersParticipationSearch),
            'state',
        )
        ->allowedIncludes([
            'conversation.latestMessage',
            'mission.responsable.avatar',
            'mission.structure',
        ])
        ->defaultSort('-created_at')
        ->paginate(config('query-builder.results_per_page'));
    }

    public function unreadMessages(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        return $user->getUnreadConversationsCount();
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $messages = [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'unique:users,email,'.$user->id],
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $user->update($request->all());

        $user->load('profile', 'profile.media', 'profile.skills', 'profile.domaines');
        $user->append(['roles']);

        return $user;
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();

        if (! (Hash::check($request->get('current_password'), $user->password))) {
            abort(422, "L'ancien mot de passe est incorrect");
        }

        if (strcmp($request->get('current_password'), $request->get('password')) == 0) {
            abort(422, 'Le nouveau mot de passe doit être différent de l\'ancien');
        }

        $messages = [
            'current_password.required' => 'Le mode de passe actuel est requis',
            'password.required' => 'Le mot de passe est requis',
            'password.min' => 'Votre nouveau mot de passe doit contenir au moins :min caractères',
            'password.confirmed' => 'Les nouveaux mots de passe ne sont pas identiques',
        ];

        $validator = Validator::make($inputs, [
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ], $messages);

        if (! $validator->fails()) {
            $user->password = Hash::make($inputs['password']);
            $user->save();

            return response()->json($user, 200);
        }

        return response()->json(['errors' => $validator->errors()], 422);
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
        $notification = new UserAnonymize($user);
        $user->notify($notification);

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
