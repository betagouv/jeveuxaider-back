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
            'listIds' => [request('id_liste')?request('id_liste'):233],
            'attributes' => request('url_mission')?array('URL_MISSION_SIGNUP' => request('url_mission')):array('URL_MISSION_SIGNUP'=>'')
        ]);

        try {
            $result = $apiInstance->createContact($createContact);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $result;
    }
}
