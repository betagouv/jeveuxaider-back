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
use App\Notifications\RegisterUserVolontaire;
use App\Http\Requests\RegisterVolontaireRequest;
use App\Http\Requests\RegisterResponsableWithStructureRequest;
use App\Models\Activity;
use App\Models\SocialAccount;
use App\Models\Structure;
use App\Services\ApiEngagement;
use Illuminate\Support\Facades\Auth;

class PassportController extends Controller
{
    use SendsPasswordResetEmails;

    public function registerVolontaire(RegisterVolontaireRequest $request)
    {
        $user = User::create(
            [
                'name' => request("email"),
                'email' => request("email"),
                'context_role' => 'volontaire',
                'password' => Hash::make(request("password"))
            ]
        );

        $attributes = $request->validated();
        $attributes['user_id'] = $user->id;

        Profile::firstOrCreate(
            ['email' => request('email')],
            $attributes
        );
        $user->refresh();

        $notification = new RegisterUserVolontaire($user);
        $user->notify($notification);

        return User::with(['profile.structures', 'profile.participations'])->where('id', $user->id)->first();
    }

    public function registerResponsable(RegisterResponsableWithStructureRequest $request)
    {
        $user = User::create(
            [
                'name' => request("email"),
                'email' => request("email"),
                'password' => Hash::make(request("password")),
                'context_role' => 'responsable',
            ]
        );
        $attributes = $request->validated();
        $attributes['user_id'] = $user->id;
        $profile = Profile::firstOrCreate(
            ['email' => request('email')],
            $attributes
        );

        $structureAttributes = [
            'user_id' => $user->id,
            'name' => request('structure_name'),
        ];

        // MAPPING API ENGAGEMENT
        if ($request->has('structure_api') && $request->input('structure_api')) {
            $structureAttributes = array_merge(
                $structureAttributes,
                ApiEngagement::prepareStructureAttributes($request->input('structure_api'))
            );
        }

        $structure = Structure::create($structureAttributes);

        // MAPPING DOMAINES ACTIONS API ENGAGEMENT
        if ($request->has('structure_api') && $request->input('structure_api')) {
            $domaine = ApiEngagement::prepareStructureDomaines($request->input('structure_api'));
            if ($domaine) {
                $structure->attachTag($domaine, 'domaine');
            }
        }
        // UPDATE LOG
        Activity::where('subject_type', 'App\Models\Structure')
            ->where('subject_id', $structure->id)
            ->where('description', 'created')
            ->update(
                [
                    'causer_id' => $user->id,
                    'causer_type' => 'App\Models\User',
                    'data' => [
                        "subject_title" => $structure->name,
                        "full_name" => $profile->full_name,
                        "causer_id" => $profile->id,
                        "context_role" => 'responsable'
                    ]
                ]
            );
            
        return User::with(['profile.structures', 'profile.participations'])->where('id', $user->id)->first();
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $socialAccount = SocialAccount::where(['user_id' => $user->id, 'provider' => 'franceconnect'])->first();
        if ($socialAccount) {
            $franceConnectLogoutUrl = config('services.franceconnect.url') . "/api/v1/logout?"
                . http_build_query(
                    [
                        'id_token_hint' => $socialAccount->data['id_token'],
                        'state' => 'franceconnect',
                        'post_logout_redirect_uri' => config('app.url')
                    ]
                );
        }

        $bearerToken = request()->bearerToken();
        $tokenId = (new Parser())->parse($bearerToken)->getClaim('jti');
        $token = Token::find($tokenId);

        $token->revoke();

        return [
            'token' => $token,
            'franceConnectLogoutUrl' => isset($franceConnectLogoutUrl) ? $franceConnectLogoutUrl : null
        ];
    }

    public function forgotPassword(Request $request)
    {
        $messages = [
            'email.required' => 'Un email est requis',
            'email.email' => 'Le format de l\'email est invalide'
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'email'],
            ],
            $messages
        );

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 401);
        }

        $response = $this->broker()->sendResetLink(
            ['email' => mb_strtolower($request->input('email'))]
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Un lien de réinitialisation de votre mot de passe a été envoyé par mail'], 201)
            : response()->json(['errors' => ['email' => ['Impossible de vous envoyer un lien de réinitialisation de votre mot de passe ']]], 401);
    }
}
