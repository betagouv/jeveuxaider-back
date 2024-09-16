<?php

namespace App\Services;

use App\Jobs\UserSetHardBouncedAt;
use App\Jobs\UsersSetHardBouncedAt;
use App\Jobs\UserUpdateLastInteractionAt;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Propaganistas\LaravelPhone\Exceptions\NumberParseException;
use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Support\Facades\Log;

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
            ->$method("https://api.sendinblue.com/v3{$path}");

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

    public static function updateContactEmail(User $user, $oldEmail)
    {
        return self::api(
            'put',
            "/contacts/$oldEmail",
            [
                'json' => [
                    'attributes' => self::formatAttributes($user),
                ],
            ]
        );
    }

    public static function deleteContact($email)
    {
        return self::api(
            'delete',
            "/contacts/$email"
        );
    }

    public static function sync(User $user, $withSMS = true)
    {
        // Don't sync if archived, anonymous or banned
        if ($user->archived_at || $user->anonymous_at || $user->banned_at) {
            return;
        }

        if(!$user->profile) {
            return;
        }

        $response = self::updateContact($user, $withSMS);

        if (!$response->successful() && $response['code'] == 'document_not_found') {
            $response = self::createContact($user, $withSMS);
        }

        if (!$response->successful()) {
            switch ($response['code']) {
                case 'invalid_parameter':
                case 'duplicate_parameter':
                    switch ($response['message']) {
                        case 'Unable to update contact, SMS is already associate with another Contact':
                        case 'Unable to update contact, SMS is already associated with another Contact':
                        case 'SMS is already associate with another Contact':
                        case 'SMS is already associated with another Contact':
                        case 'Unable to create contact, SMS is already associate with another Contact':
                        case 'Unable to create contact, SMS is already associated with another Contact':
                        case 'Invalid phone number':
                            self::sync($user, false);
                            break;
                    }
                    break;
            }
        }

        return $response;
    }

    public static function formatAttributes(User $user, $withSMS = true)
    {
        $organisation = $user->structures->first();
        if ($organisation) {
            $organisation->loadCount(['missions']);
        }

        $attributes = [
            'EMAIL' => $user->profile->email,
            'NOM' => $user->profile->last_name,
            'PRENOM' => $user->profile->first_name,
            'DATE_DE_NAISSANCE' => $user->profile->birthday?->format('Y-m-d') ?: "",
            'CODE_POSTAL' => $user->profile->zip,
            'VILLE' => $user->profile->city,
            'DEPARTEMENT' => substr($user->profile->zip, 0, 2),
            'DATE_INSCRIPTION' => $user->created_at->format('Y-m-d'),
            'NB_DEMANDE_PARTICIPATION' => $user->profile->participations->count(),
            'NB_PARTICIPATION_VALIDE_EFFECTUE' => $user->profile->participations->whereIn('state', ['Validée'])->count(),
            'ORGA_ID' => $organisation?->id ?: "",
            'ORGA_NAME' => $organisation?->name ?: "",
            'ORGA_CODE_POSTAL' => $organisation?->zip ?: "",
            'ORGA_NB_MISSION' => $organisation?->missions_count ?: "",
            'ORGA_STATE' => $organisation?->state ?: "",
            'REFERENT_DEPARTEMENT' => $user->departmentsAsReferent->first()?->number ?: "",
            'REFERENT_REGION' => $user->regionsAsReferent->first()?->name ?: "",
            'IS_VISIBLE' => $user->profile->is_visible,
            'DISPONIBILITES' => $user->profile->disponibilities,
            'DISPO_TIME_DURATION' => $user->profile->commitment__duration, // Obsolete
            'DISPO_TIME_PERIOD' => $user->profile->commitment__time_period, // Obsolete
            'ENGAGEMENT' => $user->profile->commitment,
            'ACTIVITES' => $user->profile->activities->pluck('name')->join(', '),
        ];

        if ($withSMS) {
            $phoneNumberUtil = \libphonenumber\PhoneNumberUtil::getInstance();
            $mobile = ($user->profile->mobile && $phoneNumberUtil->isPossibleNumber($user->profile->mobile, 'FR')) ? $phoneNumberUtil->parse($user->profile->mobile, 'FR') : "";
            $attributes['SMS'] = $mobile ? $phoneNumberUtil->format($mobile, \libphonenumber\PhoneNumberFormat::E164) : "";
        }

        return $attributes;
    }

    public static function onHardBounce($payload)
    {
        UserSetHardBouncedAt::dispatch($payload['email'], Carbon::now());
    }

    public static function onLinkClick($payload)
    {
        UserUpdateLastInteractionAt::dispatch($payload);
    }

    public static function syncHardBouncedUsers()
    {
        $response = self::api('get', '/smtp/blockedContacts');
        if ($response->getStatusCode() === 200) {
            $response = $response->json();
            $offset = 0;
            $limit = 100;

            do {
                UsersSetHardBouncedAt::dispatch($offset, $limit);
                $offset += $limit;
            } while ($offset < $response['count']);
        }
    }

    public static function setHardBouncedAtUsers($offset, $limit)
    {
        $response = self::api('get', "/smtp/blockedContacts?limit={$limit}&offset={$offset}");
        if ($response->getStatusCode() === 200) {
            $response = $response->json();
            foreach ($response['contacts'] as $contact) {
                if (!isset($contact['reason']['code']) || $contact['reason']['code'] !== 'hardBounce') {
                    continue;
                }
                UserSetHardBouncedAt::dispatch($contact['email'], $contact['blockedAt']);
            }
        }
    }

    public static function sendSmsMessage(String $sender, String $recipient, String $content, String $tag = '')
    {
        try {
            $formattedRecipient = (new PhoneNumber($recipient, 'FR'))->formatE164();
        } catch (NumberParseException $exeption) {
            return Log::warning('Error parsing phone', [
                'number' => $exeption->getNumber(),
                'message' => $exeption->getMessage(),
            ]);
        }

        $payload = [
            'sender' => $sender,
            'recipient' => $formattedRecipient,
            'content' => $content,
            'unicodeEnabled' => true // Augmente le nb de crédits nécessaires si true
        ];

        if (!empty($tag)) {
            $payload['tag'] = $tag;
        }

        $response = self::api(
            'post',
            '/transactionalSMS/sms',
            [
                'json' => $payload,
            ]
        );

        // ray([
        //     'statusCode' => $response->getStatusCode(),
        //     'data' => $response->json()
        // ]);

        return [
            'statusCode' => $response->getStatusCode(),
            'data' => $response->json()
        ];
    }
}
