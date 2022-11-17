<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Emailable
{
    private static function api($method, $path, $options = [])
    {
        $response = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
            ]
        )
            ->withOptions($options)
            ->$method("https://api.emailable.com/v1${path}");

        return $response;
    }

    public static function verify($email)
    {
        $key = config('services.emailable.key');
        $response = self::api('get', "/verify?email={$email}&api_key={$key}");
        return self::formatResponse($response);
    }

    private static function formatResponse($response)
    {
        return [
            'code' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'content' => $response->json(),
        ];
    }
}
