<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Parser;
use Laravel\Passport\Token;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use App\Notifications\RegisterUserResponsable;
use App\Notifications\RegisterUserVolontaire;
use App\Http\Requests\RegisterVolontaireRequest;
use App\Http\Requests\RegisterResponsableRequest;
use App\Http\Requests\RegisterResponsableWithStructureRequest;
use App\Models\Activity;
use App\Notifications\RegisterUserCollectivity;
use App\Models\Structure;

//use App\Rules\Lowercase;

class PassportController extends Controller
{
    use SendsPasswordResetEmails;

    public function registerVolontaire(RegisterVolontaireRequest $request)
    {
        $user = User::create([
            'name' => request("email"),
            'email' => request("email"),
            'context_role' => 'volontaire',
            'password' => Hash::make(request("password"))
        ]);

        $profile = Profile::whereEmail(request('email'))->first();

        if (!$profile) { // S'il n'y a pas de Profile, c'est une inscription sans invitation, donc un responsable
            $profile = Profile::create($request->validated());
        }


        $user->profile()->save($profile);

        $notification = new RegisterUserVolontaire($user);
        $user->notify($notification);

        return $user;
    }

    public function registerResponsable(RegisterResponsableWithStructureRequest $request)
    {
        $user = User::create([
            'name' => request("email"),
            'email' => request("email"),
            'password' => Hash::make(request("password"))
        ]);

        $profile = Profile::whereEmail(request('email'))->first();

        if (!$profile) { // S'il n'y a pas de Profile, c'est une inscription sans invitation, donc un responsable
            $profile = Profile::create($request->validated());
        }
        $user->profile()->save($profile);

        $structure = Structure::create(
            ['user_id' => $user->id, 'name' => request('structure_name')]
        );

        // UPDATE LOG
        $activity = Activity::where('subject_type', 'App\Models\Structure')
            ->where('subject_id', $structure->id)
            ->where('description', 'created')
            ->update([
                'causer_id' => $user->id,
                'causer_type' => 'App\Models\User',
                'data' => [
                    "subject_title" => $structure->name,
                    "full_name" => $profile->full_name,
                    "causer_id" => $profile->id,
                    "context_role" => 'responsable'
                ]
            ]);

        $notification = new RegisterUserResponsable($structure);
        $user->notify($notification);

        return $user;
    }

    public function registerCollectivity(RegisterResponsableRequest $request)
    {
        $user = User::create([
            'name' => request("email"),
            'email' => request("email"),
            'context_role' => 'volontaire', // On ne peut pas mettre collectivity car il doit être validé par un modérateur
            'password' => Hash::make(request("password"))
        ]);

        $profile = Profile::whereEmail(request('email'))->first();

        if (!$profile) { // S'il n'y a pas de Profile, c'est une inscription sans invitation, donc un responsable
            $profile = Profile::create($request->validated());
        }

        $notification = new RegisterUserCollectivity($user);
        $user->profile()->save($profile);
        $user->notify($notification);

        return $user;
    }

    public function registerInvitation(RegisterResponsableRequest $request)
    {
        $user = User::create([
            'name' => request("email"),
            'email' => request("email"),
            'password' => Hash::make(request("password"))
        ]);

        $profile = Profile::whereEmail(request('email'))->first();

        if (!$profile) { // S'il n'y a pas de Profile, c'est une inscription sans invitation, donc un responsable
            $profile = Profile::create($request->validated());
        }

        $user->profile()->save($profile);

        return $user;
    }

    public function logout(Request $request)
    {
        $bearerToken = request()->bearerToken();
        $tokenId = (new Parser())->parse($bearerToken)->getClaim('jti');
        $token = Token::find($tokenId);

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
            'email' => ['required','email'],
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
