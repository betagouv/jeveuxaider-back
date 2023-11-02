<?php

use App\Models\Structure;
use App\Models\User;
use Laravel\Passport\Passport;

use function Pest\Faker\fake;

it('can register and create a structure', function () {
    $userData = [
        'email' =>  fake()->email,
        'password' => 'password123',
        'first_name' => fake()->firstName,
        'last_name' => fake()->lastName,
        'mobile' => fake('fr_FR')->phoneNumber,
        'birthday' => '1980-01-02',
        'zip' => fake('fr_FR')->postcode,
        'structure_name' => fake()->company,
        'structure_statut_juridique' => "Association"
    ];

    $response = $this->post('/api/register/responsable', $userData);

    $response->assertStatus(201);

    $user = User::latest()->first();
    $structure = $user->structures->first();

    expect($user)
        ->email->toBe($userData['email'])
        ->context_role->toBe('responsable')
        ->structures->count()->toBe(1)
        ->hasRole("responsable")->toBe(true);

    expect($structure->name)->toBe($userData['structure_name']);
});

it('can edit my structure', function () {
    $structure = Structure::factory()->create();
    $user = $structure->user;

    Passport::actingAs($user);

    $structureDatas = [
        'name' => fake()->company,
        'description' =>  fake()->paragraph,
    ];

    $response = $this->put('/api/structures/' . $structure->id, $structureDatas, [
        'Context-Role' => $user->context_role
    ]);

    $structure->refresh();
    $response->assertStatus(200);

    expect($structure)
        ->name->toBe($structureDatas['name'])
        ->description->toBe($structureDatas['description']);
});

it('cannot edit other structure', function () {
    $structure1 = Structure::factory()->create();
    $structure2 = Structure::factory()->create();
    $user1 = $structure1->user;

    Passport::actingAs($user1);

    $structureDatas = [
        'name' => fake()->company,
        'description' =>  fake()->paragraph,
    ];

    $response = $this->put('/api/structures/' . $structure2->id, $structureDatas, [
        'Context-Role' => $user1->context_role
    ]);

    $response->assertStatus(403);
});
