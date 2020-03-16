<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Models\Structure;

$factory->define(Structure::class, function (Faker $faker) {
    $statuts = config('taxonomies.structure_legal_status.terms');

    return [
        'name' => $faker->name,
        'description' => $faker->sentence(4),
        'statut_juridique' => $statuts['Association'],
        'is_reseau' => 1
    ];
});
