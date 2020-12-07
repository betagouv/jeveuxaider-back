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

        $url = env('FC_URL') . env('AUTHORIZATION_FC_PATH');

        return $url . "?" . http_build_query($query);
    }

    public function oauthLoginCallback()
    {
        // Set request params
        $body = [
      'grant_type' => 'authorization_code',
      'redirect_uri' => env('FS_URL') . env('LOGIN_CALLBACK_FS_PATH'),
      'client_id' => env('AUTHENTICATION_CLIENT_ID'),
      'client_secret' => env('AUTHENTICATION_CLIENT_SECRET'),
      'code' => 'req.query.code'
    ];

        // Request access token.
        /*
        const { data: { access_token: accessToken, id_token: idToken } } = await httpClient({
          method: 'POST',
          headers: { 'content-type': 'application/x-www-form-urlencoded' },
          data: querystring.stringify(body),
          url: `${config.FC_URL}${config.TOKEN_FC_PATH}`,
        });
        */
        /*
            if (!accessToken || !idToken) {
              return res.sendStatus(401);
            }
            */

        // Request user data
        /*
        const { data: user } = await httpClient({
          method: 'GET',
          headers: { Authorization: `Bearer ${accessToken}` },
          url: `${config.FC_URL}${config.USERINFO_FC_PATH}`,
        });
        */

        // Store the user and context in session so it is available for future requests
        // as the idToken for Logout
        // req.session.user = user;
        // req.session.idTokenPayload = getPayloadOfIdToken(idToken);
        // req.session.idToken = idToken;

        // return res.redirect('/user');

        return $body;
    }
}
