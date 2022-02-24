<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SendInBlueController extends Controller
{
    public function store()
    {
        $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.sendinblue.key'));
        $config = \SendinBlue\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', config('services.sendinblue.key'));

        $apiInstance = new \SendinBlue\Client\Api\ContactsApi(
            new \GuzzleHttp\Client(),
            $config
        );
        $createContact = new \SendinBlue\Client\Model\CreateContact([
            'email' => request('email'),
            'attributes' => [
                'CODE_POSTAL' => request('zipcode') ? request('zipcode') : '',
                'DEPARTEMENT' => request('department') ? request('department') : '',
                'URL_MISSION_SIGNUP' => request('url_mission') ? request('url_mission') : '',
            ],
            'listIds' => [request('id_liste') ? request('id_liste') : 233],
        ]);

        try {
            if (config('app.env') === 'production') {
                $result = $apiInstance->createContact($createContact);
                return $result;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return false;
    }
}
