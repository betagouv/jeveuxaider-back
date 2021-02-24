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
            'listIds' => [233]
        ]);

        try {
            $result = $apiInstance->createContact($createContact);
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $result;
    }
}
