<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\Models\User;
use App\Models\Structure;
use App\Models\Profile;

class UserTest extends TestCase
{
    use WithFaker, WithoutMiddleware, DatabaseTransactions;

    /** @test */
    public function a_user_can_register()
    {
        $email = $this->faker->unique()->safeEmail;

        $attributes = [
            'name' => $this->faker->name,
            'email' => $email,
            'first_name' => 'Firstname',
            'last_name' => 'Lastname',
            'password' => $this->faker->password(8),
        ];

        $response = $this->post('api/register', $attributes);

        $this->assertDatabaseHas('users', ['email' => $email]);
        $response->assertStatus(201);
    }

    /** @test */
    public function a_user_can_create_a_structure()
    {
        // $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $profile = factory(Profile::class)->create();
        $user->profile()->save($profile);

        $attributes = factory(Structure::class)->raw();
        $response = $this->actingAs($user)->post('api/structure', $attributes);

        $this->assertDatabaseHas('structures', $attributes);
        $response->assertStatus(201);
    }
}
