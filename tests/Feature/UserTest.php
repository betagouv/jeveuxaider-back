<?php

use App\Models\Message;
use App\Models\Mission;
use App\Models\Participation;
use App\Models\User;
use Laravel\Passport\Passport;

use function Pest\Faker\fake;

it('can register as benevole', function () {
    $email = fake()->email;

    $userData = [
        'email' => $email,
        'password' => 'password123',
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'mobile' => fake('fr_FR')->phoneNumber,
        'birthday' => '1980-01-02',
        'zip' => fake('fr_FR')->postcode,
        'country' => 'FR',
    ];

    $response = $this->post('/api/register/volontaire', $userData);

    $response->assertStatus(201);

    $user = User::latest()->first();

    expect($user)
        ->email->toBe($userData['email'])
        ->context_role->toBe('volontaire');
});

it('can edit your own profile', function () {
    $user = Passport::actingAs(
        User::factory()->create()
    );

    $newPhoneNumber = fake('fr_FR')->phoneNumber;
    $newEmail = fake()->unique()->safeEmail();

    $response = $this->put('/api/profiles/' . $user->profile->id, [
        'mobile' => $newPhoneNumber,
        'email' => $newEmail
    ]);

    $response->assertStatus(200);

    $user->refresh();

    expect($user->profile)
        ->mobile->toBe($newPhoneNumber)
        ->email->toBe($newEmail);
    expect($user)
        ->email->toBe($newEmail);

});

it('can not edit others profiles', function () {
    Passport::actingAs(
        User::factory()->create()
    );

    $otherUser = User::factory()->create();

    $newEmail = fake()->unique()->safeEmail();

    $response = $this->put('/api/profiles/' . $otherUser->profile->id, [
        'email' => $newEmail
    ]);

    $response->assertStatus(403);
});

it('cannot participate to a mission which is not validated', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $mission = Mission::factory()->create();

    $response = $this->post('/api/participations', [
        'mission_id' => $mission->id,
        'profile_id' => $user->profile->id,
        'content' => fake()->paragraph()
    ]);

    $response->assertStatus(422);

    expect($user->profile->participations->count())->toBe(0);
});

it('can participate to a mission which is validated', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $mission = Mission::factory()->validated()->create();

    $response = $this->post('/api/participations', [
        'mission_id' => $mission->id,
        'profile_id' => $user->profile->id,
        'content' => fake()->paragraph()
    ]);

    $response->assertStatus(201);

    expect($user->profile->participations->count())->toBe(1);
});

it('can write a message in his conversation', function () {

    $participation = Participation::factory()->create();

    Passport::actingAs($participation->profile->user);

    $content = fake()->paragraph();

    $response = $this->post('/api/conversations/' . $participation->conversation->id . '/messages', [
        'content' => $content
    ]);

    $response->assertStatus(201);

    $message = Message::latest('id')->first();

    expect($message->content)->toBe($content);
});

it('cannot write a message in a conversation of other', function () {

    $participation = Participation::factory()->create();

    Passport::actingAs(User::factory()->create());

    $content = fake()->paragraph();

    $response = $this->post('/api/conversations/' . $participation->conversation->id . '/messages', [
        'content' => $content
    ]);

    $response->assertStatus(403);
});

it('can cancel my participation and write message', function () {

    $participation = Participation::factory()->create();
    $places_left = $participation->mission->places_left;

    Passport::actingAs($participation->profile->user);

    $content = fake()->paragraph();

    $response = $this->put('/api/participations/' . $participation->id . '/cancel-by-benevole', [
        'reason' => 'not_available',
        'content' => $content
    ]);

    $message = Message::latest('id')->first();

    $participation->refresh();

    expect($participation->state)->toBe("Annulée");
    expect($message->content)->toBe($content);
    expect($participation->mission->places_left)->toBe($places_left + 1);

    $response->assertStatus(200);
});

it('can unsubscribe', function () {
    $user = User::factory()->create();

    Passport::actingAs($user);

    $response = $this->post('/api/user/anonymize');

    expect($user)
        ->email->toBe($user->id . '@anonymized.fr')
        ->profile->first_name->toBe('Anonyme')
        ->profile->last_name->toBe('Anonyme');

    $response->assertStatus(200);
});
