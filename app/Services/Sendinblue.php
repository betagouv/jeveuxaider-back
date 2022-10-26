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
                ],
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
                ],
            ]
        );
    }

    public static function sync(User $user, $withSMS = true)
    {
        if ($withSMS) {
            // ray('WITH MOBILE : Sendinblue sync user ' . $user->email . ' '.$user->profile->mobile);
        } else {
            // ray('WITHOUT MOBILE : Sendinblue sync user ' . $user->email);
        }
        $response = self::updateContact($user, $withSMS);

        if (! $response->successful() && $response['code'] == 'document_not_found') {
            $response = self::createContact($user, $withSMS);
        }

        if (! $response->successful()) {
            if ($response['code'] == 'duplicate_parameter') {
                switch ($response['message']) {
                    case 'Unable to update contact, SMS is already associate with another Contact':
                    case 'SMS is already associate with another Contact':
                    case 'Unable to create contact, SMS is already associate with another Contact':
                        self::sync($user, false);
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
            'NB_PARTICIPATION_VALIDE_EFFECTUE' => $user->profile->participations->whereIn('state', ['Validée'])->count(),
            'ORGA_NAME' => $organisation ? $organisation->name : null,
            'ORGA_CODE_POSTAL' => $organisation ? $organisation->zip : null,
            'ORGA_NB_MISSION' => $organisation ? $organisation->missions->count() : null,
            'REFERENT_DEPARTEMENT' => $user->departmentsAsReferent->first() ? $user->departmentsAsReferent->first()->number : null,
            'REFERENT_REGION' => $user->regionsAsReferent->first() ? $user->regionsAsReferent->first()->name : null,
            'IS_VISIBLE' => $user->profile->is_visible,
            'DISPONIBILITES' => $user->profile->disponibilities,
            'DISPO_TIME_DURATION' => $user->profile->commitment__duration,
            'DISPO_TIME_PERIOD' => $user->profile->commitment__time_period,
            'ACTIVITES' => $user->profile->activities->pluck('name')->join(', '),
        ];

        if ($withSMS) {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $mobile = ($user->profile->mobile && $phoneNumberUtil->isPossibleNumber($user->profile->mobile, 'FR')) ? $phoneNumberUtil->parse($user->profile->mobile, 'FR') : null;
            $attributes['SMS'] = $mobile ? $phoneNumberUtil->format($mobile, \libphonenumber\PhoneNumberFormat::E164) : null;
        }

        return $attributes;
    }
}
