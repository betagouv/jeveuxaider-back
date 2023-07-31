<?php

namespace Database\Factories;

use App\Models\Mission;
use App\Models\Participation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Participation>
 */
class ParticipationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = User::factory()->create();

        return [
            'mission_id' => Mission::factory(),
            'profile_id' => $user->profile->id,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Participation $participation) {
            $currentUser = $participation->profile->user;

            $conversation = $currentUser->startConversation($participation->mission->responsable->user, $participation);
            $currentUser->sendMessage($conversation->id, fake()->paragraph());
            $currentUser->markConversationAsRead($participation->conversation);
        });
    }
}
