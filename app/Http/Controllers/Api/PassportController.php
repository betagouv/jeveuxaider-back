<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterResponsableWithStructureRequest;
use App\Http\Requests\RegisterVolontaireRequest;
use App\Jobs\DeleteUserArchivedDatas;
use App\Models\ActivityLog;
use App\Models\Profile;
use App\Models\SocialAccount;
use App\Models\Structure;
use App\Models\User;
use App\Notifications\RegisterUserVolontaire;
use App\Notifications\RegisterUserVolontaireCejAdviser;
use App\Notifications\RegisterUserVolontaireFTAdviser;
use App\Services\ApiEngagement;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class PassportController extends Controller
{
    use SendsPasswordResetEmails;

    public function registerVolontaire(RegisterVolontaireRequest $request)
    {
        $user = User::forceCreate(
            [
                'name' => request('email'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'context_role' => 'volontaire',
                'utm_source' => request('utm_source'),
                'utm_campaign' => request('utm_campaign'),
                'utm_medium' => request('utm_medium'),
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

        // Can be set from soft gate register
        if ($user->profile->cej && !empty($user->profile->cej_email_adviser)) {
            Notification::route('mail', $user->profile->cej_email_adviser)
                ->notify(new RegisterUserVolontaireCejAdviser($user->profile));
        }

        if ($user->profile->ft && !empty($user->profile->ft_email_adviser)) {
            Notification::route('mail', $user->profile->ft_email_adviser)
                ->notify(new RegisterUserVolontaireFTAdviser($user->profile));
        }

        DeleteUserArchivedDatas::dispatch($user->email);

        return $user;
    }

    public function registerResponsable(RegisterResponsableWithStructureRequest $request)
    {
        $user = User::forceCreate(
            [
                'name' => request('email'),
                'email' => request('email'),
                'password' => Hash::make(request('password')),
                'context_role' => 'responsable',
                'utm_source' => request('utm_source'),
                'utm_campaign' => request('utm_campaign'),
                'utm_medium' => request('utm_medium'),
            ]
        );
        $attributes = $request->validated();
        $attributes['user_id'] = $user->id;
        $attributes['is_visible'] = false;

        $profile = Profile::firstOrCreate(
            ['email' => request('email')],
            $attributes
        );

        $structureAttributes = [
            'user_id' => $user->id,
            'name' => request('structure_name'),
            'statut_juridique' => request('structure_statut_juridique'),
        ];

        // MAPPING API ENGAGEMENT
        if ($request->has('structure_api') && $request->input('structure_api')) {
            $structureAttributes = array_merge(
                $structureAttributes,
                ApiEngagement::prepareStructureAttributes($request->input('structure_api'))
            );
        }

        $structure = Structure::create($structureAttributes);

        $user->contextable_type = 'structure';
        $user->contextable_id = $structure->id;
        $user->save();

        DeleteUserArchivedDatas::dispatch($user->email);

        // UPDATE LOG
        ActivityLog::where('subject_type', 'App\Models\Structure')
            ->where('subject_id', $structure->id)
            ->where('description', 'created')
            ->update(
                [
                    'causer_id' => $user->id,
                    'causer_type' => 'App\Models\User',
                    'data' => [
                        'subject_title' => $structure->name,
                        'full_name' => $profile->full_name,
                        'causer_id' => $profile->id,
                        'context_role' => 'responsable',
                    ],
                ]
            );

        return $user;
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('api')->user();
        $socialAccount = SocialAccount::where(['user_id' => $user->id, 'provider' => 'franceconnect'])->first();
        if ($socialAccount) {
            $franceConnectLogoutUrl = config('services.franceconnect.url') . '/api/v1/logout?'
                . http_build_query(
                    [
                        'id_token_hint' => $socialAccount->data['id_token'],
                        'state' => 'franceconnect',
                        'post_logout_redirect_uri' => config('app.front_url'),
                    ]
                );
        }

        $request->user()->token()->revoke();

        return [
            'franceConnectLogoutUrl' => isset($franceConnectLogoutUrl) ? $franceConnectLogoutUrl : null,
        ];
    }

    public function forgotPassword(Request $request)
    {
        $messages = [
            'email.required' => 'Un email est requis',
            'email.email' => 'Le format de l\'email est invalide',
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

        $user = User::where('email', $request->input('email'))->first();

        if ($user && $user->isBlocked()) {
            return response()->json(['message' => "L'email que vous avez renseigné est incorrect"], 401);
        }

        $response = $this->broker()->sendResetLink(
            ['email' => mb_strtolower($request->input('email'))]
        );

        return $response == Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Un lien de réinitialisation de votre mot de passe a été envoyé par mail'], 201)
            : response()->json(['message' => "L'email que vous avez renseigné est incorrect"], 401);
    }
}
