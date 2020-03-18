<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Validator;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Notifications\RegisterUserResponsable;
use App\Notifications\RegisterUserVolontaire;
use App\Http\Requests\RegisterRequest;

class PassportController extends Controller
{
    use SendsPasswordResetEmails;

    public function register(RegisterRequest $request)
    {
        if (!$request->validated()) {
            return $request->validated();
        }

        $user = User::create([
            'name' => request("email"),
            'email' => request("email"),
            'password' => Hash::make(request("password"))
        ]);

        $profile = Profile::whereEmail(request('email'))->first();

        if (!$profile) { // S'il n'y a pas de Profile, c'est une inscription sans invitation, donc un responsable
            $profile = Profile::create($request->validated());
        }

        if (request('type') == 'volontaire') {
            $notification = new RegisterUserVolontaire($user);
        }

        if (request('type') == 'responsable') {
            $notification = new RegisterUserResponsable($user);
        }

        $user->profile()->save($profile);

        $user->notify($notification);

        return $user;
    }

    public function logout(Request $request)
    {
        $id = (new Parser())->parse($request->bearerToken())->getHeader('jti');
        $token = Token::find($id);
        $token->revoke();

        return $token;
    }

    public function forgotPassword(Request $request)
    {
        $messages = [
            'email.required' => 'Un email est requis',
            'email.email' => 'Le format de l\'email est invalide'
        ];

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ], $messages);

        if ($validator->fails()) {
            return response()->json(['errors'=> $validator->errors()], 401);
        }

        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Un lien de réinitialisation de votre mot de passe a été envoyé par mail'], 201)
            : response()->json(['errors' => ['email' => ['Impossible de vous envoyer un lien de réinitialisation de votre mot de passe ']]], 401);
    }
}
