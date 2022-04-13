<?php

use App\Models\Mission;
use App\Models\Structure;
use App\Services\Airtable;
use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('airtable', function () {
    // $query = Mission::with(['structure', 'domaine', 'template.domaine'])
    // ->whereIn('state', ['En attente de validation', 'En cours de traitement', 'Validée'])
    // ->where("id", 114);
    $query = Structure::whereIn('state', ['En attente de validation', 'En cours de traitement', 'Validée'])
    ->where("id", 43);


    $query->chunk(50, function ($objects) {
        foreach ($objects as $object) {
            // Airtable::syncMission($mission);
            Airtable::syncStructure($object);
        }
    });

})->describe('Display an inspiring quote');
