<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class SendInBlueController extends Controller
{
    public function store()
    {
        $config = \Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', config('services.sendinblue.key'));
        $config = \Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('partner-key', config('services.sendinblue.key'));

        $apiInstance = new \Brevo\Client\Api\ContactsApi(
            new \GuzzleHttp\Client(),
            $config
        );
        $createContact = new \Brevo\Client\Model\CreateContact([
            'email' => request('email'),
            'attributes' => [
                'CODE_POSTAL' => request('zipcode') ? request('zipcode') : '',
                'DEPARTEMENT' => request('department') ? request('department') : '',
                'URL_MISSION_SIGNUP' => request('url_mission') ? request('url_mission') : '',
            ],
            'listIds' => [request('id_liste') ? request('id_liste') : 233],
        ]);

        try {
            if (config('services.sendinblue.sync')) {
                $result = $apiInstance->createContact($createContact);

                return $result;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return false;
    }
}
