<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\SocialAccount;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\RegisterUserVolontaire;

class FranceConnectController extends Controller
{
    public function oAuthLoginAuthorize()
    {
        $query = [
          'scope' => 'openid given_name family_name preferred_username birthdate email',
          'redirect_uri' => config('app.url') . '/login',
          'response_type' => 'code',
          'client_id' => config('services.franceconnect.client_id'),
          'state' => Str::uuid()->toString(),
          'nonce' => Str::random(20),
          'acr_values' => 'eidas1'
        ];

        return config('services.franceconnect.url') . "/api/v1/authorize?" . http_build_query($query);
    }

    public function oauthLoginCallback(Request $request)
    {
        $response = Http::asForm()->post(config('services.franceconnect.url') . '/api/v1/token', [
          'grant_type' => 'authorization_code',
          'redirect_uri' => config('app.url') . '/login',
          'client_id' => config('services.franceconnect.client_id'),
          'client_secret' => config('services.franceconnect.client_secret'),
          'code' =>  $request->query('code')
        ]);

        if (!isset($response['access_token']) || !isset($response['id_token'])) {
            return response()->json(['message' => "France Connect connexion failed. No access token"], 401);
        }
        // Request user data
        $franceConnectUser = Http::withToken($response['access_token'])->get(config('services.franceconnect.url') . '/api/v1/userinfo');

        $user = User::where('email', strtolower($franceConnectUser['email']))->first();
        if (!$user) {
            $user = $this->register($franceConnectUser);
        }

        SocialAccount::updateOrCreate(
            ['provider' => 'franceconnect', 'user_id' => $user->id],
            ['provider_user_id' => $franceConnectUser['sub'], 'data' => array_merge($franceConnectUser->json(), $response->json())]
        );

        return $user->createToken('franceconnect');
    }

    private function register($franceConnectUser)
    {
        $user = User::create([
          'name' => $franceConnectUser['email'],
          'email' => $franceConnectUser['email'],
          'context_role' => 'volontaire',
          'password' => Hash::make(Str::random(12))
        ]);

        $profile = Profile::firstOrCreate(
            ['email' => $franceConnectUser['email']],
            [
                'user_id' => $user->id,
                'first_name' => $franceConnectUser['given_name'] ?? '',
                'last_name' => $franceConnectUser['family_name'] ?? '',
                'birthday' => $franceConnectUser['birthdate'] ?? ''
            ]
        );

        $user->profile()->save($profile);

        $notification = new RegisterUserVolontaire($user);
        $user->notify($notification);

        return User::with(['profile.structures', 'profile.participations','socialAccounts'])->where('id', $user->id)->first();
    }
}
