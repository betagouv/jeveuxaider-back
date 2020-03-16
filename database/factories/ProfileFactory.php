<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Models\Profile;

$factory->define(Profile::class, function (Faker $faker) {
    return [
        'first_name' => $faker->name(4),
        'last_name' => $faker->name(6),
    ];
});
