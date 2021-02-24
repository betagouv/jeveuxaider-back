<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\Event;
use App\Models\Participation;
use App\Models\Mission;
use App\Models\Profile;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

// To prevent emails from being sent,
// Also prevent an error -> too many emails per second
Event::fake();

$factory->define(Participation::class, function (Faker $faker) {
    $state = array_rand(config('taxonomies.participation_workflow_states.terms'));

    // @TODO: Corriger. Ne marche que si l'on créé du contenu 1 par 1, car sinon $mission n'est jamais mis a jour.
    $mission = Mission::inRandomOrder()->has('participations', '<', DB::raw('participations_max'))->limit(1)->first();
    if (empty($mission)) {
        $mission = factory(App\Models\Mission::class)->create();
    }

    $profile = Profile::inRandomOrder()->whereDoesntHave('participations', function (Builder $query) use ($mission) {
        $query->where('mission_id', $mission->id);
    })->limit(1)->first();
    if (!$profile) {
        $profile = factory(App\Models\Profile::class)->create();
    }

    return [
        'profile_id' => $profile->id,
        'mission_id' => $mission->id,
        'state' => $state,
    ];
});
