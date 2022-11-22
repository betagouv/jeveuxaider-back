<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class Emailable
{
    private static function api($method, $path, $options = [])
    {
        try {
            $response = Http::withHeaders(
                ['Content-Type' => 'application/json']
            )
                ->withOptions($options)
                ->$method("https://api.emailable.com/v1${path}");

            return $response;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function verify($email)
    {
        $key = self::getApiKey();
        $response = self::api('get', "/verify?email={$email}&api_key={$key}");
        return self::formatResponse($response);
    }

    private static function formatResponse($response)
    {
        if ($response instanceof \Exception) {
            $key = self::getApiKey();
            return [
                'code' => $response->getCode(),
                'content' => str_replace("&api_key={$key}", '', $response->getMessage()),
            ];
        }

        return [
            'code' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'content' => $response->json(),
        ];
    }

    private static function getApiKey()
    {
        return config('services.emailable.key');
    }
}
