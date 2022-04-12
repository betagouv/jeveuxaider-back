<?php

namespace App\Services;

use App\Models\Mission;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Airtable
{
    private static function api($method, $path, $options = [])
    {
        $response = Http::withHeaders(
            [
                'Content-Type' => 'application/json',
            ]
        )
        ->withToken(config('services.airtable.key'))
        ->withOptions($options)
        ->$method("https://api.airtable.com/v0/" . config('services.airtable.base') . $path);

        return $response;
    }

    public static function getMissionAirtableId(Int $id)
    {
        $recordsAirtable = Http::withToken(config('services.airtable.key'))
            ->get('https://api.airtable.com/v0/' . config('services.airtable.base') . '/' .  config('services.airtable.table'), 
            [
                'filterByFormula' => '{Id} = ' . $id,
            ]);

        return count($recordsAirtable['records']) > 0 ? $recordsAirtable['records'][0]["id"] : false;
    }

    public static function syncMission(Mission $mission) 
    {
        $missionAirtableId = self::getMissionAirtableId($mission->id); 
        ray('get missionAirtableId', $missionAirtableId);

        if(!$missionAirtableId) {
            ray('not exist, create mission');
            return self::createMission($mission);
        } else {
            ray('exist !, update mission');
            return self::updateMission($missionAirtableId, $mission);
        }
    }

    public static function createMission(Mission $mission)
    {
        return self::api(
            'post',
            '/' . config('services.airtable.table'),
            [
                'json' => [
                    'fields' => self::formatAttributes($mission)
                ]
            ]
        );
    }

    public static function updateMission(String $missionAirtableId, Mission $mission)
    {
        return self::api(
            'patch',
            '/' . config('services.airtable.table') . '/' . $missionAirtableId,
            [
                'json' => [
                    'fields' => self::formatAttributes($mission)
                ]
            ]
        );
    }

    public static function formatAttributes(Mission $mission)
    {
        $attributes = [
            'Id' => $mission->id,
            'Title' => $mission->name,
            'Statut' => $mission->state,
            'Code Postal' => $mission->zip,
            'Département' => $mission->department,
            'Places restantes' => $mission->places_left,
            'Places max' => $mission->participations_max,
            'Présentiel / À distance' => $mission->type,
            'Domaine' => $mission->domaine ? $mission->domaine->name : $mission->template->domaine->name,
            'Date de début' =>  Carbon::create($mission->start_date)->format("m-d-Y"), // mm-dd-YYYY
            'Date de fin' =>  Carbon::create($mission->end_date)->format("m-d-Y"),
            'Organisation' => $mission->structure->name,
            'Organisation Statut Juridique' => $mission->structure->statut_juridique,
            'Organisation Id' => $mission->structure->id,
            'Crée le' => Carbon::create($mission->created_at)->format("m-d-Y"),
            'Modifié le' => Carbon::create($mission->updated_at)->format("m-d-Y"),
        ];

        return $attributes;
    }
}
