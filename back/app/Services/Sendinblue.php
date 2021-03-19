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

    public static function createContact(User $user)
    {
        return self::api(
            'post',
            '/contacts',
            [
            'json' => [
                'email' => $user->email,
                'attributes' => self::formatAttributes($user),
            ]
            ]
        );
    }

    public static function updateContact(User $user)
    {
        return self::api(
            'put',
            "/contacts/$user->email",
            [
                'json' => [
                    'attributes' => self::formatAttributes($user),
                ]
            ]
        );
    }

    public static function sync(User $user)
    {
        $response = self::updateContact($user);
        if (!$response->successful() && $response['code'] == 'document_not_found') {
            $response = self::createContact($user);
        }

        if (!$response->successful()) {
            report(new \Exception("Sendinblue sync failed for user $user->email with code " . $response['code']));
        }
        return $response;
    }

    public static function formatAttributes(User $user)
    {
        $organisation = $user->profile->structureAsResponsable();
        $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        $mobile = ($user->profile->mobile && $phoneNumberUtil->isPossibleNumber($user->profile->mobile, 'FR')) ? $phoneNumberUtil->parse($user->profile->mobile, 'FR') : null;

        if ($user->profile->mobile && !$phoneNumberUtil->isPossibleNumber($user->profile->mobile, 'FR')) {
            dump("$user->email has a wrong phone number : " . $user->profile->mobile);
        }

        $attributes = [
            // TODO : EMAIL attributes if email has changed
            'NOM' => $user->profile->last_name,
            'PRENOM' => $user->profile->first_name,
            'SMS' => $mobile ? $phoneNumberUtil->format($mobile, \libphonenumber\PhoneNumberFormat::E164) : null,
            'DATE_DE_NAISSANCE' => $user->profile->birthday ? $user->profile->birthday->format('Y-m-d') : null,
            'CODE_POSTAL' => $user->profile->zip,
            'DEPARTEMENT' => substr($user->profile->zip, 0, 2),
            'DATE_INSCRIPTION' => $user->created_at->format('Y-m-d'),
            'NB_DEMANDE_PARTICIPATION' => $user->profile->participations->count(),
            'NB_PARTICIPATION_VALIDE_EFFECTUE' => $user->profile->participations->whereIn("state", ["Validée", "Effectuée"])->count(),
            'ORGA_NAME' => $organisation ? $organisation->name : null,
            'ORGA_CODE_POSTAL' => $organisation ? $organisation->zip : null,
            'ORGA_NB_MISSION' => $organisation ? $organisation->missions->count() : null,
        ];

        return $attributes;
    }
}
