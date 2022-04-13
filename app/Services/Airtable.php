<?php

namespace App\Services;

use App\Models\Mission;
use App\Models\Structure;
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
            ->$method("https://api.airtable.com/v0/" . config('services.airtable.base') . $path);


            if(!$response->successful()) {
                throw new \Exception("Invalid response from Airtable (".$response->status().") : " . $response->body());
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

    public static function deleteObject($type, Mission|Structure $object) 
    {
        $objectAirtableId = self::getAirtableId($type, $object->id); 

        if($objectAirtableId) {
            return self::api(
                'delete',
                $type == 'mission' ? '/Missions/' . $objectAirtableId : '/Organisations/' . $objectAirtableId,
            );
        }
    }

    private static function syncObject($type, $fields) 
    {
        $objectAirtableId = self::getAirtableId($type, $fields['Id']); 

        if(!$objectAirtableId) {
            return self::createObject($type, $fields);

        } else {
            return self::updateObject($type, $objectAirtableId, $fields);
        }
    }

    private static function getAirtableId($type, Int $id)
    {
        $path = $type == 'mission' ? '/Missions' : '/Organisations';
        $recordsAirtable = Http::withToken(config('services.airtable.key'))
            ->get('https://api.airtable.com/v0/' . config('services.airtable.base') . $path, 
            [
                'filterByFormula' => '{Id} = ' . $id,
            ]);
        return count($recordsAirtable['records']) > 0 ? $recordsAirtable['records'][0]["id"] : false;
    }

    private static function createObject($type, $fields)
    {
        return self::api(
            'post',
            $type == 'mission' ? '/Missions' : '/Organisations',
            [
                'json' => [
                    'fields' => $fields
                ]
            ]
        );
    }

    private static function updateObject($type, String $objectAirtableId, $fields)
    {
        return self::api(
            'patch',
            $type == 'mission' ? '/Missions/' . $objectAirtableId : '/Organisations/' . $objectAirtableId,
            [
                'json' => [
                    'fields' => $fields
                ]
            ]
        );
    }

    private static function formatMissionAttributes(Mission $mission)
    {
        $attributes = [
            'Id' => $mission->id,
            'Title' => $mission->name,
            'Statut' => $mission->state,
            'Code Postal' => $mission->zip,
            'Département' => $mission->department,
            'Adresse' => $mission->address,
            'Places restantes' => $mission->places_left,
            'Places max' => $mission->participations_max,
            'Présentiel / À distance' => $mission->type,
            'Domaine' => $mission->domaine ? $mission->domaine->name : $mission->template->domaine->name,
            'Date de début' =>  Carbon::create($mission->start_date)->format("m-d-Y"), // mm-dd-YYYY
            'Date de fin' =>  Carbon::create($mission->end_date)->format("m-d-Y"),
            'Organisation Id' => $mission->structure->id,
            'Organisation' => $mission->structure->name,
            'Organisation Statut' => $mission->structure->state,
            'Organisation Statut Juridique' => $mission->structure->statut_juridique,
            'URL' => config('app.front_url') . $mission->full_url,
            'Description' => $mission->objectif,
            'Crée le' => Carbon::create($mission->created_at)->format("m-d-Y"),
            'Modifiée le' => Carbon::create($mission->updated_at)->format("m-d-Y"),
        ];

        return $attributes;
    }

    private static function formatStructureAttributes(Structure $structure)
    {
        $attributes = [
            'Id' => $structure->id,
            'Nom' => $structure->name,
            'Statut' => $structure->state,
            'Département' => $structure->department,
            'Statut Juridique' => $structure->statut_juridique,
            'Bénévoles recherchés' => $structure->places_left,
            'Taux de réponse' => $structure->response_ratio,
            'Temps de réponse' => $structure->response_time / (60 * 60 * 24),
            'URL' => config('app.front_url') . $structure->full_url,
            'Crée le' => Carbon::create($structure->created_at)->format("m-d-Y"),
            'Modifiée le' => Carbon::create($structure->updated_at)->format("m-d-Y"),
        ];

        return $attributes;
    }
}
