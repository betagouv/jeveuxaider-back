<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Rules\Lowercase;
use Illuminate\Support\Facades\Validator;
use Laravel\Passport\Token;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index()
    {
        return QueryBuilder::for(User::class)
                ->allowedFilters('name', 'is_admin')
                ->defaultSort('-created_at')
                ->paginate(config('query-builder.results_per_page'));
    }

    public function show(Request $request)
    {
        $user = $request->user();
        $user['profile']['roles'] = $user->profile->roles;

        return $user;
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $messages = [
            'email.required' => 'Un email est requis',
            'email.unique' => 'Cet email est déjà pris',
        ];

        $validator = Validator::make($request->all(), [
            'email' => ['required','email','unique:users,email,' . $user->id, new Lowercase]
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()], 400);
        }

        $user->update($request->all());

        return $user;
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();

        if (!(Hash::check($request->get('current_password'), $user->password))) {
            return response()->json(['errors'=> [
                'current_password' => [
                    "L'ancien mot de passe est incorrect"
                ]
            ]], 400);
        }

        if (strcmp($request->get('current_password'), $request->get('password')) == 0) {
            return response()->json(['errors'=> [
                'password' => [
                    'Le nouveau mot de passe doit être différent de l\'ancien',
                ]
            ]], 400);
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

        if (!$validator->fails()) {
            $user->password = Hash::make($inputs['password']);
            $user->save();
            return response()->json($user, 200);
        }
        return response()->json(['errors'=> $validator->errors()], 400);
    }

    public function impersonate(User $user)
    {
        return $user->createToken('impersonate');
    }

    public function stopImpersonate(Token $token)
    {
        return (string) $token->revoke();
    }

    public function anonymize(Request $request)
    {
        // TODO : Seulement volontaire peuvent s'anonymiser
        $user = $request->user();
        $user->anonymize();
        $user->token()->revoke();

        return $user;
    }
}
