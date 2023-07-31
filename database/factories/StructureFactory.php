<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Structure>
 */
class StructureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company,
            'description' => fake()->paragraph(),
            'user_id' =>  User::factory()
            // statut juridique
            // domaines d'action
            // publics bénéficiaires
            // département
            // téléphone public
        ];
    }
}
