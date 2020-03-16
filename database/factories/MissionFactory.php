<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Models\Mission;

$factory->define(Mission::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'description' => $faker->sentence(4),
        'user_id' => 1,
        'structure_id' => 1,
    ];
});
