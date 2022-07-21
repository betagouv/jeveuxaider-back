<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Snu
{
    public function getWaitingActionsFromEmail($email)
    {
        try {
            $response = Http::get(config('app.snu_api_url').'/jeveuxaider/actions', [
                'email' => $email,
                'token' => config('app.snu_api_token'),
            ]);

            return isset($response['data']) ? $response['data']['actions'] : null;
        } catch (ConnectionException $e) {
            throw $e;
        }
    }
}
