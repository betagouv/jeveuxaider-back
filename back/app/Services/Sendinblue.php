<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class Sendinblue
{
    private static function api($method, $path, $options = [])
    {
        $response = Http::withHeaders(
            [
                'api-key' => config('services.sendinblue.key'),
                'Content-Type' => 'application/json',
            ]
        )
        ->withOptions($options)
        ->$method("https://api.sendinblue.com/v3${path}");

        return $response;
    }

    public static function createContact(User $user, $withSMS = true)
    {
        return self::api(
            'post',
            '/contacts',
            [
                'json' => [
                    'email' => $user->email,
                    'attributes' => self::formatAttributes($user, $withSMS),
                ]
            ]
        );
    }

    public static function updateContact(User $user, $withSMS = true)
    {
        return self::api(
            'put',
            "/contacts/$user->email",
            [
                'json' => [
                    'attributes' => self::formatAttributes($user, $withSMS),
                ]
            ]
        );
    }

    public static function sync(User $user, $withSMS = true)
    {

        $response = self::updateContact($user, $withSMS);

        if (!$response->successful() && $response['code'] == 'document_not_found') {
            $response = self::createContact($user, $withSMS);
        }

        if (!$response->successful()) {
            if ($response['code'] == 'duplicate_parameter') {
                switch ($response['message']) {
                    case "Unable to update contact, SMS is already associate with another Contact":
                    case "SMS is already associate with another Contact":
                        self::sync($user, false);
                        break;
                    default:
                        report(new \Exception("Sendinblue sync failed for user $user->email with code " . $response['code'] . ' and message ' . $response['message']));
                        break;
                }
            }
        }

        return $response;
    }

    public static function formatAttributes(User $user, $withSMS = true)
    {
        $organisation = $user->profile->structureAsResponsable();

        $attributes = [
            // TODO : EMAIL attributes if email has changed
            'NOM' => $user->profile->last_name,
            'PRENOM' => $user->profile->first_name,
            'DATE_DE_NAISSANCE' => $user->profile->birthday ? $user->profile->birthday->format('Y-m-d') : null,
            'CODE_POSTAL' => $user->profile->zip,
            'DEPARTEMENT' => substr($user->profile->zip, 0, 2),
            'DATE_INSCRIPTION' => $user->created_at->format('Y-m-d'),
            'NB_DEMANDE_PARTICIPATION' => $user->profile->participations->count(),
            'NB_PARTICIPATION_VALIDE_EFFECTUE' => $user->profile->participations->whereIn("state", ["Validée"])->count(),
            'ORGA_NAME' => $organisation ? $organisation->name : null,
            'ORGA_CODE_POSTAL' => $organisation ? $organisation->zip : null,
            'ORGA_NB_MISSION' => $organisation ? $organisation->missions->count() : null,
        ];

        if ($withSMS) {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $mobile = ($user->profile->mobile && $phoneNumberUtil->isPossibleNumber($user->profile->mobile, 'FR')) ? $phoneNumberUtil->parse($user->profile->mobile, 'FR') : null;
            $attributes['SMS'] = $mobile ? $phoneNumberUtil->format($mobile, \libphonenumber\PhoneNumberFormat::E164) : null;
        }

        return $attributes;
    }
}
