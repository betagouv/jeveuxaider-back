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
        $user = User::with('profile', 'profile.media', 'profile.skills')->where('id', Auth::guard('api')->user()->id)->first();
        $user->append(['roles']);
        $user->profile->append(['avatar', 'domaines']);

        return $user;
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
        $user->append(['roles']);
        $user->profile->append(['avatar', 'skills', 'domaines']);

        return $user;
    }

    // Doublon avec ResetPasswordController@reset

    // public function updatePassword(Request $request)
    // {
    //     $user = $request->user();
    //     $inputs = $request->all();

    //     if (!(Hash::check($request->get('current_password'), $user->password))) {
    //         return response()->json(['errors' => [
    //             'current_password' => [
    //                 "L'ancien mot de passe est incorrect"
    //             ]
    //         ]], 422);
    //     }

    //     if (strcmp($request->get('current_password'), $request->get('password')) == 0) {
    //         return response()->json(['errors' => [
    //             'password' => [
    //                 'Le nouveau mot de passe doit être différent de l\'ancien',
    //             ]
    //         ]], 422);
    //     }

    //     $messages = [
    //         'current_password.required' => 'Le mode de passe actuel est requis',
    //         'password.required' => 'Le mot de passe est requis',
    //         'password.min' => 'Votre nouveau mot de passe doit contenir au moins :min caractères',
    //         'password.confirmed' => 'Les nouveaux mots de passe ne sont pas identiques',
    //     ];

    //     $validator = Validator::make($inputs, [
    //         'current_password' => 'required',
    //         'password' => [
    //             'required',
    //             'min:8',
    //             'confirmed',
    //         ],
    //     ], $messages);

    //     if (!$validator->fails()) {
    //         $user->password = Hash::make($inputs['password']);
    //         $user->save();
    //         return response()->json($user, 200);
    //     }
    //     return response()->json(['errors' => $validator->errors()], 400);
    // }

    public function impersonate(User $user)
    {
        $token = $user->createToken('impersonate');
        $token->token->expires_at = now()->addMinutes(60);
        $token->token->save();

        return $token;
    }

    public function stopImpersonate(Token $token)
    {
        ray("coucou");
        ray($token);
        return (string) $token->revoke();
    }

    // public function anonymize(Request $request)
    // {
    //     $user = $request->user();

    //     // Si je suis le dernier responsable d'une organisation on la désinscrit
    //     if ($user->profile->structures) {
    //         foreach ($user->profile->structures as $structure) {
    //             if ($structure->members->count() == 1) {
    //                 $structure->state = 'Désinscrite';
    //                 $structure->save();
    //             }
    //         }
    //     }

    //     $user->anonymize();
    //     $user->token()->revoke();

    //     return $user;
    // }
}
