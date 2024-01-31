<?php

namespace App\Services;

use App\Models\Mission;
use App\Models\Structure;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Airtable
{
    private static function api($method, $path, $options = [])
    {
        try {
            $response = Http::withHeaders(
                [
                    'Content-Type' => 'application/json',
                ]
            )
            ->withToken(config('services.airtable.key'))
            ->withOptions($options)
            ->$method('https://api.airtable.com/v0/' . config('services.airtable.base') . $path);

            if (!$response->successful()) {
                throw new \Exception('Invalid response from Airtable (' . $response->status() . ') : ' . $response->body());
            }

            return $response;
        } catch (\Exception $e) {
            report($e->getMessage());

            return $e->getMessage();
        }
    }

    public static function syncMission(Mission $mission)
    {
        $fields = self::formatMissionAttributes($mission);

        return self::syncObject('mission', $fields);
    }

    public static function syncStructure(Structure $structure)
    {
        $fields = self::formatStructureAttributes($structure);

        return self::syncObject('structure', $fields);
    }

    public static function syncUser(User $user)
    {
        $fields = self::formatUserAttributes($user);

        if (!$user->hasRole(['referent', 'referent_regional'])) {
            return self::deleteObject('user', $user);
        }

        return self::syncObject('user', $fields);
    }

    public static function deleteObject($type, Model $object)
    {
        $id = $object->id;
        if ($type == 'user') {
            $id = $object->profile->id;
        }
        $objectAirtableId = self::getAirtableId($type, $id);

        if ($objectAirtableId) {
            return self::api(
                'delete',
                self::getAirtablePath($type) . '/' . $objectAirtableId,
            );
        }
    }

    private static function syncObject($type, $fields)
    {
        $objectAirtableId = self::getAirtableId($type, $fields['Id']);

        if (!$objectAirtableId) {
            return self::createObject($type, $fields);
        } else {
            return self::updateObject($type, $objectAirtableId, $fields);
        }
    }

    private static function getAirtableId($type, int $id)
    {
        $recordsAirtable = Http::withToken(config('services.airtable.key'))
            ->get(
                'https://api.airtable.com/v0/' . config('services.airtable.base') . self::getAirtablePath($type),
                [
                    'filterByFormula' => '{Id} = ' . $id,
                ]
            );

        return count($recordsAirtable['records']) > 0 ? $recordsAirtable['records'][0]['id'] : false;
    }

    private static function createObject($type, $fields)
    {
        return self::api(
            'post',
            self::getAirtablePath($type),
            [
                'json' => [
                    'fields' => $fields,
                ],
            ]
        );
    }

    private static function updateObject($type, string $objectAirtableId, $fields)
    {
        return self::api(
            'patch',
            self::getAirtablePath($type) . '/' . $objectAirtableId,
            [
                'json' => [
                    'fields' => $fields,
                ],
            ]
        );
    }

    private static function formatMissionAttributes(Mission $mission)
    {
        $activity = $mission->template_id ? $mission->template->activity : $mission->activity;

        $attributes = [
            'Id' => $mission->id,
            'Title' => $mission->name,
            'Statut' => $mission->state,
            'Mission en ligne' => $mission->is_online,
            'Code Postal' => $mission->zip,
            'Département' => $mission->department,
            'Adresse' => $mission->address,
            'Places restantes' => $mission->places_left,
            'Places max' => $mission->participations_max,
            'Présentiel / À distance' => $mission->type,
            'Domaine' => $mission->domaine ? $mission->domaine->name : $mission->template->domaine->name,
            'Date de début' => $mission->start_date ? Carbon::create($mission->start_date)->format('m-d-Y') : null, // mm-dd-YYYY
            'Date de fin' => $mission->end_date ? Carbon::create($mission->end_date)->format('m-d-Y') : null,
            'Organisation Id' => $mission->structure->id,
            'Organisation' => $mission->structure->name,
            'Organisation Statut' => $mission->structure->state,
            'Organisation Statut Juridique' => $mission->structure->statut_juridique,
            'Modèle de mission Id' => $mission->template ? $mission->template->id : null,
            'Activité Id' => $activity ? $activity->id : null,
            'Activité Titre' => $activity ? $activity->name : null,
            'URL' => config('app.front_url') . $mission->full_url,
            'Description' => $mission->objectif,
            'Précision' => $mission->description,
            'Quelques mots' => $mission->information,
            'Tag' => $mission->tags->count() > 0 ? $mission->tags->pluck('name')->join(', ') : null,
            'Fréquence d\'engagement' => $mission->commitment__time_period,
            'Durée d\'engagement' =>  $mission->commitment__duration,
            'Durée totale d\'Engagement' => $mission->commitment__total,
            'Date type' => $mission->date_type,
            'Inscription ouverte' => $mission->is_registration_open,
            'Prerequisites' => collect($mission->prerequisites)->count() > 0 ? collect($mission->prerequisites)->join(', ') : null,
            'Crée le' => Carbon::create($mission->created_at)->format('m-d-Y'),
            'Modifiée le' => Carbon::create($mission->updated_at)->format('m-d-Y'),
        ];

        return $attributes;
    }

    private static function formatStructureAttributes(Structure $structure)
    {
        $structure->load(['score']);
        $attributes = [
            'Id' => $structure->id,
            'Nom' => $structure->name,
            'Statut' => $structure->state,
            'Département' => $structure->department,
            'Statut Juridique' => $structure->statut_juridique,
            'Bénévoles recherchés' => $structure->places_left,
            'Taux de réponse' => $structure->score ? $structure->score->processed_participations_rate / 100 : 0,
            'Temps de réponse' => $structure->score ? $structure->score->response_time / (60 * 60 * 24) : 0,
            'Score de réactivité' => $structure->score ? $structure->score->total_points : 50,
            'Missions en ligne' => $structure->missions()->available()->count(),
            'URL' => config('app.front_url') . $structure->full_url,
            'Crée le' => Carbon::create($structure->created_at)->format('m-d-Y'),
            'Modifiée le' => Carbon::create($structure->updated_at)->format('m-d-Y'),
        ];

        return $attributes;
    }

    private static function formatUserAttributes(User $user)
    {
        foreach ($user->roles as $key => $role) {
            if (isset($role['pivot']['rolable_type'])) {
                $user->roles[$key]['pivot_model'] = $role['pivot']['rolable_type']::find($role['pivot']['rolable_id']);
            }
        }

        $roles = $user->roles->map(function ($role) {
            return $role->name;
        });
        $referentDepartments = $user->roles->where('name', 'referent')->map(function ($role) {
            return $role->pivot_model->number;
        });
        $referentRegions = $user->roles->where('name', 'referent_regional')->map(function ($role) {
            return $role->pivot_model->name;
        });

        $attributes = [
            'Id' => $user->profile->id,
            'Prénom' => $user->profile->first_name,
            'Nom' => $user->profile->last_name,
            'E-mail' => $user->profile->email,
            'Numéro' => $user->profile->mobile,
            'Département' => $referentDepartments->first(),
            'Région' => $referentRegions->first(),
            'Rôle' => $roles,
            'URL' => config('app.front_url') . '/admin/utilisateurs/' . $user->profile->id,
            'Tag' => $user->profile->tags->count() > 0 ? $user->profile->tags->first()['name'] : null,
            'Crée le' => Carbon::create($user->profile->created_at)->format('m-d-Y'),
            'Modifié le' => Carbon::create($user->profile->updated_at)->format('m-d-Y'),
        ];

        return $attributes;
    }

    private static function getAirtablePath($type)
    {
        if ($type == 'mission') {
            return '/Missions';
        } elseif ($type == 'structure') {
            return '/Organisations';
        } elseif ($type == 'user') {
            return '/Utilisateurs';
        }
    }
}
