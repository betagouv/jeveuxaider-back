<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class FormUserController extends Controller
{
    public function email(Request $request)
    {
        $user = User::find(Auth::guard('api')->user()->id);

        $messages = [
            'email.required' => 'Le champ :attribute est obligatoire.',
            'email.unique' => 'Cet e-mail est déjà utilisé.',
            'email.email' => 'Le format du champ :attribute est invalide.',
        ];

        $validator = Validator::make($request->all(), [
            'email' => [
                'email',
                'required',
                Rule::unique('users')->ignore($user->id),
            ]
        ], $messages);

        $user->update($validator->validated());
        $user->profile->update($validator->validated());

        return $user;
    }


    public function password(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();

        if (!(Hash::check($request->get('current_password'), $user->password))) {
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

        if (!$validator->fails()) {
            $user->password = Hash::make($inputs['password']);
            $user->save();

            return response()->json($user, 200);
        }

        return response()->json(['errors' => $validator->errors()], 422);
    }

}
