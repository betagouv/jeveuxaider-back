<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Models\Structure;
use Illuminate\Support\Facades\Event;

// To prevent emails from being sent,
// Also prevent an error -> too many emails per second
Event::fake();

$factory->define(Structure::class, function (Faker $faker) {
    $profile = factory(App\Models\Profile::class)->create();
    $statuts = array_rand(config('taxonomies.structure_legal_status.terms'));
    $associationType = $statuts == 'Association' ? array_rand(config('taxonomies.association_types.terms')) : null;
    $organizationPublicType = $statuts == 'Structure publique' ? array_rand(config('taxonomies.structure_publique_types.terms')) : null;
    $organizationPrivateType = $statuts == 'Structure privée' ? array_rand(config('taxonomies.structure_privee_types.terms')) : null;
    $isReseau = $faker->boolean();

    return [
        'user_id' => $profile->user->id,
        'name' => $faker->company,
        'description' => $faker->sentence(40),
        'statut_juridique' => $statuts,
        'is_reseau' => $isReseau,
        'reseau_id' => $isReseau ? Structure::inRandomOrder()->first()->id : null,
        'siret' => $faker->siret,
        'structure_publique_type' => $organizationPublicType,
        'structure_privee_type' => $organizationPrivateType,
        'association_types' => $associationType,
        'address' => $faker->streetAddress,
        'zip' => $faker->postcode,
        'city' => $faker->city,
        'country' => $faker->country,
        'department' => $faker->departmentNumber,
        'latitude' => $faker->latitude,
        'longitude' => $faker->longitude
    ];
});

$factory->afterCreating(Structure::class, function ($structure, $faker) {
    $structure->members()->attach($structure->user->profile, ['role' => 'responsable']);
});
