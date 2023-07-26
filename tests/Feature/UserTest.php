<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

use function Pest\Faker\fake;

it('can register as benevole', function () {
    $email = fake()->email;

    $userData = [
        'name' => $email,
        'email' => $email,
        'password' => Hash::make('password123'),
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
