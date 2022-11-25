<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ActivityClassifier
{
    private static function api($method, $path, $body = [])
    {
        try {
            $response = Http::withHeaders(
                ['Content-Type' => 'application/json']
            )
                ->withToken(self::getToken())
                ->withBody($body, 'json')
                // @todo faire marcher 'wait_for_model,
                // ->withOptions(['wait_for_model' => true])
                ->$method("https://api-inference.huggingface.co/models/jeveuxaider${path}");

            return $response;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function evaluate($payload)
    {
        $response = self::api('post', "/activity-classifier", mb_substr($payload, 0, 512));
        return self::formatResponse($response);
    }

    private static function formatResponse($response)
    {
        if ($response instanceof \Exception) {
            return [
                'code' => $response->getCode(),
                'content' => $response->getMessage(),
            ];
        }

        return [
            'code' => $response->getStatusCode(),
            'reason' => $response->getReasonPhrase(),
            'content' => $response->json(),
        ];
    }

    private static function getToken()
    {
        return config('services.activityclassifier.token');
    }
}
