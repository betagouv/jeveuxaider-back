<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

class FranceConnectController extends Controller
{
    public function oAuthLoginAuthorize()
    {
        $eidasLevel = 'eidas1';
        $scopes = 'openid given_name family_name gender preferred_username birthdate';
        $query = [
      'scope' => $scopes,
      'redirect_uri' => env('FS_URL') . env('LOGIN_CALLBACK_FS_PATH'),
      'response_type' => 'code',
      'client_id' => env('AUTHENTICATION_CLIENT_ID'),
      'state' => 'home',
      'nonce' => 'customNonce11',
      'acr_values' => $eidasLevel
    ];

        dump($query);

        $url = env('FC_URL') . env('AUTHORIZATION_FC_PATH');

        dump(url($url, $query));


        dump($url);

        return $url;
    
        //   return res.redirect(`${url}?${querystring.stringify(query)}`);
    }
}
