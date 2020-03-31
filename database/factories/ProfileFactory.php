<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use Faker\Generator as Faker;
use App\Models\Profile;

$factory->define(Profile::class, function (Faker $faker) {
    $user = factory(App\Models\User::class)->create();

    return [
        'first_name' => $faker->name(4),
        'last_name' => $faker->name(6),
        'user_id' => $user->id,
        'email' => $user->email,
        'phone' => $faker->phoneNumber,
        'mobile' => $faker->mobileNumber,
        'birthday' => $faker->dateTime('18 years ago'),
        'service_civique' => $faker->boolean(),
        'is_analyste' => $faker->boolean(),
        'zip' => $faker->postcode
    ];
});
