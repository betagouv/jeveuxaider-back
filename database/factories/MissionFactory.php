<?php

namespace Database\Factories;

use App\Models\Structure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mission>
 */
class MissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'structure_id' => Structure::factory(),
            'user_id' => function (array $attributes) {
                return Structure::find($attributes['structure_id'])->user_id;
            },
            'responsable_id' => function (array $attributes) {
                return User::find($attributes['user_id'])->profile->id;
            },
            'participations_max' => fake()->numberBetween(1, 100)
        ];
    }

    public function validated(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'state' => 'Validée',
            ];
        });
    }
}
