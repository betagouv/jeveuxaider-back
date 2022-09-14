<?php

namespace App\Services;

use GuzzleHttp\Client;

class ApiAdresse
{
    // https://adresse.data.gouv.fr/api-doc/adresse
    public static function search($query)
    {
        if (empty($query['q'])) {
            return;
        }

        $client = new Client();
        $response = $client->request('GET', 'https://api-adresse.data.gouv.fr/search?'.http_build_query($query));
        $response = json_decode($response->getBody()->getContents(), true);

        return (isset($query['limit']) && $query['limit'] === 1) ? reset($response['features']) : $response['features'];
    }
}
