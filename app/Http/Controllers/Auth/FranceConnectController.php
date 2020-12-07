<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class FranceConnectController extends Controller
{
    public function oAuthLoginAuthorize()
    {
        $query = [
          'scope' => 'openid given_name family_name preferred_username birthdate email',
          'redirect_uri' => env('FS_URL') . '/franceconnect/login-callback',
          'response_type' => 'code',
          'client_id' => env('AUTHENTICATION_CLIENT_ID'),
          'state' => 'home',
          'nonce' => 'customNonce11',
          'acr_values' => 'eidas1'
        ];

        return env('FC_URL') . "/api/v1/authorize?" . http_build_query($query);
    }

    public function oauthLoginCallback(Request $request)
    {
        $response = Http::asForm()->post(env('FC_URL') . '/api/v1/token', [
          'grant_type' => 'authorization_code',
          'redirect_uri' => env('FS_URL') . '/franceconnect/login-callback',
          'client_id' => env('AUTHENTICATION_CLIENT_ID'),
          'client_secret' => env('AUTHENTICATION_CLIENT_SECRET'),
          'code' =>  $request->query('code')
        ]);

        debug($response->json());
        
        if (!isset($response['access_token']) || !isset($response['id_token'])) {
            return response()->json(['message' => "France Connect connexion failed. No access token"], 401);
        }
            
        // Request user data
        $user = Http::withToken($response['access_token'])->get(env('FC_URL') . '/api/v1/userinfo');
        debug($user->json());

        return $user;


        




        // Store the user and context in session so it is available for future requests
        // as the idToken for Logout
        // req.session.user = user;
        // req.session.idTokenPayload = getPayloadOfIdToken(idToken);
        // req.session.idToken = idToken;

        // return res.redirect('/user');

        return $body;
    }
}
