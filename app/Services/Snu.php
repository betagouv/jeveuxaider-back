<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Snu
{
    public function getTokenJvaByEmail($email)
    {
        try {
            $response = Http::get(config('app.snu_api_url').'/jeveuxaider/getToken', [
                'email' => $email,
                'api_key' => config('app.snu_api_token'),
            ]);
            return isset($response['data']) ? $response['data']['token_jva'] : null;
        } catch (ConnectionException $e) {
            throw $e;
        }
    }

    public function getWaitingActionsFromEmail($email)
    {
        try {
            $response = Http::get(config('app.snu_api_url').'/jeveuxaider/actions', [
                'email' => $email,
                'api_key' => config('app.snu_api_token'),
            ]);
            return isset($response['data']) ? $response['data']['actions'] : null;
        } catch (ConnectionException $e) {
            throw $e;
        }
    }
}
