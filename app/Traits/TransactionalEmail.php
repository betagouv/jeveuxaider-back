<?php

namespace App\Traits;

trait TransactionalEmail
{
    public $tag;

    private function trackedUrl($path)
    {
        $parsedUrl = parse_url($path);
        $queryArray = [];

        if (isset($parsedUrl['query'])) {
            foreach (explode('&', $parsedUrl['query']) as $param) {
                $parts = explode('=', $param);
                if (count($parts) === 2) {
                    list($key, $value) = array_map('urldecode', $parts);
                    $queryArray[$key] = $value;
                }
            }
        }

        if (empty($queryArray['utm_source'])) {
            $queryArray['utm_source'] = 'transactionnel';
        }
        if (empty($queryArray['utm_campaign']) && !empty($this->tag)) {
            $queryArray['utm_campaign'] = $this->tag;
        }

        $processedUrl = config('app.front_url') . $parsedUrl['path'];
        if (!empty($queryArray)) {
            $processedUrl .= '?' . http_build_query($queryArray);
        }

        if (!empty($parsedUrl['fragment'])) {
            $processedUrl .= '#' . urlencode($parsedUrl['fragment']);
        }

        return $processedUrl;
    }
}
