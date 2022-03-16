<?php

namespace App\Services;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class Snu
{
    public function getWaitingActionsFromEmail($email)
    {
        try {
            $response = Http::get(config('app.snu_api_url') . '/jeveuxaider/actions', [
                'email' => $email,
                'token' => config('app.snu_api_token')
            ])->throw();

            return isset($response['data']) ? $response['data']['actions'] : null;
        } catch (ConnectionException $e) {
            throw $e;
        }
    }
}
