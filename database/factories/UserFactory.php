<?php

namespace Database\Factories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $email = fake()->unique()->safeEmail();

        return [
            'name' => $email,
            'email' => $email,
            'password' => Hash::make(Str::random(10)),
            'context_role' => 'volontaire'
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (User $user) {
            Profile::create(
                [
                    'email' => $user->email,
                    'user_id' => $user->id,
                    'first_name' => fake()->firstName,
                    'last_name' => fake()->lastName,
                    'mobile' => fake('fr_FR')->phoneNumber,
                    'birthday' => '10/05/1990',
                    'zip' => fake('fr_FR')->postcode,
                    'country' => 'FR'
                ]
            );
            $user->load('profile');
        });
    }
}
