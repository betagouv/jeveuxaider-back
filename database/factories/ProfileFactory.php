<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Helpers\Utils;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Event;
use App\Models\Profile;

// To prevent emails from being sent,
// Also prevent an error -> too many emails per second
Event::fake();

$factory->define(Profile::class, function (Faker $faker) {
    $user = factory(App\Models\User::class)->create();

    return [
        'first_name' => Utils::removeAccents($faker->firstName),
        'last_name' => Utils::removeAccents($faker->lastName),
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
