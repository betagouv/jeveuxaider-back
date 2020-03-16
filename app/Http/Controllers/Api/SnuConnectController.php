<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;
use Illuminate\Support\Facades\Crypt;

class SnuConnectController extends Controller
{
    public function show($token)
    {
        try {
            $token = (new Parser())->parse((string) $token);
            $data = new ValidationData();
            if ($token->validate($data) !== true) { // It will use the current time to validate (iat, nbf and exp)
                abort(400, "token has expired");
            }
            if ($token->verify(new Sha256(), config('app.key')) !== true) { // Verify Signature
                abort(400, "token is not valid");
            }
            return [
                "snu_id" => Crypt::decryptString($token->getClaim("snu_id")),
                "first_name" => Crypt::decryptString($token->getClaim("first_name")),
                "last_name" => Crypt::decryptString($token->getClaim("last_name")),
                "email" => Crypt::decryptString($token->getClaim("email"))
            ];
        } catch (Exception $e) {
            abort(400, $e->getMessage());
        }
    }

    public function generateUserToken($first_name, $last_name, $email, $id)
    {
        $time = time();
        $signer = new Sha256();

        $token = (new Builder())->issuedAt($time) // Configures the time that the token was issue (iat claim)
            ->expiresAt($time + 3600) // Configures the expiration time of the token (exp claim)
            ->withClaim('snu_id', Crypt::encryptString($id)) // Configures a new claim, called "uid"
            ->withClaim('first_name', Crypt::encryptString($first_name)) // Configures a new claim, called "uid"
            ->withClaim('last_name', Crypt::encryptString($last_name)) // Configures a new claim, called "uid"
            ->withClaim('email', Crypt::encryptString($email)) // Configures a new claim, called "uid"
            ->getToken($signer, new Key(config('app.key'))); // Retrieves the generated token

        return $token;
    }
}
