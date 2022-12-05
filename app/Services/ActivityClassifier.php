<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ActivityClassifier
{
    private static function api($method, $path, $body, $token)
    {
        try {
            $response = Http::withHeaders(
                ['Content-Type' => 'application/json']
            )
                ->withToken($token)
                ->withBody($body, 'json')
                // @todo faire marcher 'wait_for_model,
                // ->withOptions(['wait_for_model' => true])
                ->$method($path);


            return $response;
        } catch (\Exception $e) {
            return $e;
        }
    }

    public static function evaluate($payload)
    {
        $response = self::api(
            'post',
            "https://x69z25ki7w4chwbn.eu-west-1.aws.endpoints.huggingface.cloud",
            json_encode(['inputs' => mb_substr($payload, 0, 512)]),
            config('services.activityclassifier.token')
        );
        return self::formatResponse($response);
    }

    public static function sortedOptions($payload)
    {
        $response = self::api(
            'post',
            "https://api-inference.huggingface.co/models/jeveuxaider/activity-classifier",
            json_encode(['inputs' => mb_substr($payload, 0, 512)]),
            config('services.activityclassifier.free_token')
        );
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
}
