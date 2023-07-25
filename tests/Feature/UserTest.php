<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        'birthday' => fake()->date(),
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
