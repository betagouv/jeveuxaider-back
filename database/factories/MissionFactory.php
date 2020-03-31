<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Event;
use App\Models\Mission;
use App\Models\Structure;

// To prevent emails from being sent,
// Also prevent an error -> too many emails per second
Event::fake();

$factory->define(Mission::class, function (Faker $faker) {
    $structure = $faker->numberBetween(0, 100) > 33 ? Structure::inRandomOrder()->limit(1)->first() : factory(Structure::class)->create();
    $domain = array_rand(config('taxonomies.mission_domaines.terms'));
    $missionFormat = array_rand(config('taxonomies.mission_formats.terms'));
    $missionPeriod = array_rand(config('taxonomies.mission_periodicites.terms'));
    $missionState = array_rand(config('taxonomies.mission_workflow_states.terms'));
    $missionPublicsBeneficiaires = (array) array_rand(config('taxonomies.mission_publics_beneficiaires.terms'), $faker->numberBetween(1, 5));
    $missionType = array_rand(config('taxonomies.mission_types.terms'));
    $missionPublicsVolontaires = (array) array_rand(config('taxonomies.mission_publics_volontaires.terms'), $faker->numberBetween(1, 2));

    return [
        'user_id' => $structure->user->id,
        'tuteur_id' => $structure->user->profile->id,
        'structure_id' => $structure->id,
        'name' => $domain,
        'description' => $faker->sentence(40),
        'participations_max' => $faker->numberBetween(1, 2000),
        'format' => $missionFormat,
        'start_date' => $faker->dateTimeBetween('+1 day', '+1 month'),
        'end_date' => $faker->dateTimeBetween('+1 month', '+3 months'),
        'dates_infos' => $faker->sentence(20),
        'periodicite' => $missionPeriod,
        'address' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'country' => $faker->country,
        'department' => $faker->departmentNumber,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude,
        'state' => $missionState,
        'publics_beneficiaires' => $missionPublicsBeneficiaires,
        'publics_volontaires' => $missionPublicsVolontaires,
        'type' => $missionType,
    ];
});
