<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiQPV
{
    public static function isQPV($address, $postal_code, $city)
    {
        if(!config('services.qpv.username') && !config('services.qpv.password')) {
            return;
        }

        if( $address == $city ) { // Adresse non précise
            return false;
        }

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . base64_encode(config('services.qpv.username').':'.config('services.qpv.password'))
        ])->get('https://wsa.sig.ville.gouv.fr/service/georeferenceur.json', [
            'type_quartier' => 'QP',
            'type_adresse' => 'MIXTE',
            'adresse[code_postal]' => $postal_code,
            'adresse[nom_commune]' => $city,
            'adresse[nom_voie]' => $address,
        ]);

        if($response->ok()) {
            $jsonResponse = $response->json();
            return isset($jsonResponse['code_reponse']) && $jsonResponse['code_reponse'] == 'OUI';
        }

        return false;
    }
}
